<?php

namespace OurNew\BotCore\Telegram\Middlewares;

use OurNew\BotCore\Models\Chat;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;

class CheckMaintenance
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $chat = $bot->getData(Chat::class);
        
        if (app()->isDownForMaintenance()) {
            
            if ($chat->chat_id === config('owner.id')) {
                $bot->sendMessage('<b>⚠ MAINTENANCE MODE ENABLED ⚠</b>', [
                    'parse_mode' => ParseMode::HTML,
                ]);
                $next($bot);
                
                return;
            }
            
            if ($bot->isCallbackQuery()) {
                $bot->answerCallbackQuery([
                    'text' => trans('maintenance.active'),
                    'show_alert' => true,
                ]);
                
                return;
            }
            
            $bot->sendMessage(message('maintenance'), [
                'parse_mode' => ParseMode::HTML,
            ]);
            
            return;
        }
        
        $next($bot);
    }
}
