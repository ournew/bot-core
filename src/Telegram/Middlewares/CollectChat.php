<?php

namespace OurNew\BotCore\Telegram\Middlewares;

use Illuminate\Support\Facades\DB;
use OurNew\BotCore\Models\Chat;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

class CollectChat
{
    public function __invoke(Nutgram $bot, $next): void
    {
        //get user
        $user = $bot->user();
        
        //if user is null, return
        if ($user === null) {
            return;
        }
        
        //get chat type
        $chatType = match ($bot->update()?->getType()) {
            UpdateTypes::MESSAGE => $bot->update()->message->chat->type,
            UpdateTypes::EDITED_MESSAGE => $bot->update()->edited_message->chat->type,
            UpdateTypes::CHANNEL_POST => $bot->update()->channel_post->chat->type,
            UpdateTypes::EDITED_CHANNEL_POST => $bot->update()->edited_channel_post->chat->type,
            UpdateTypes::INLINE_QUERY,
            UpdateTypes::CHOSEN_INLINE_RESULT,
            UpdateTypes::CALLBACK_QUERY,
            UpdateTypes::SHIPPING_QUERY,
            UpdateTypes::PRE_CHECKOUT_QUERY,
            UpdateTypes::POLL_ANSWER,
            UpdateTypes::MY_CHAT_MEMBER,
            UpdateTypes::CHAT_MEMBER,
            UpdateTypes::CHAT_JOIN_REQUEST => 'private',
            default => 'unknown',
        };
        
        //if chat type is not private, return
        if ($chatType !== 'private') {
            return;
        }
        
        //collect user
        $chat = DB::transaction(function () use ($user) {
            //save or update chat
            $chat = Chat::updateOrCreate([
                'chat_id' => $user->id,
            ], [
                'type' => 'private',
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'language_code' => $user->language_code,
            ]);
            
            //if chat has not started_at, set it
            if (!$chat->started_at) {
                $chat->started_at = now();
                $chat->save();
            }
            
            return $chat;
        });
        
        //save chat in bot data
        $bot->setData(Chat::class, $chat);
        
        //call next action
        $next($bot);
    }
}