<?php

namespace OurNew\BotCore\Telegram\Conversations;

use OurNew\BotCore\Models\Feedback;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use function OurNew\BotCore\Helpers\stats;

class FeedbackConversation extends Conversation
{
    protected bool $success = false;
    protected int $chat_id;
    protected int $message_id;

    /**
     * Ask for feedback text
     * @param Nutgram $bot
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot): void
    {
        $message = $bot->sendMessage(message('feedback.ask', vendor: 'bot-core'), [
            'reply_markup' => InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make(
                    text: trans('bot-core::common.cancel'),
                    callback_data: 'feedback.cancel'
                )),
        ]);

        $this->chat_id = $message->chat->id;
        $this->message_id = $message->message_id;

        $this->next('getFeedback');

        stats('feedback', 'command');
    }

    /**
     * Get the feedback message
     * @param Nutgram $bot
     * @throws InvalidArgumentException
     */
    public function getFeedback(Nutgram $bot): void
    {
        //handle cancel button
        if ($bot->isCallbackQuery() && $bot->callbackQuery()->data === 'feedback.cancel') {
            $bot->answerCallbackQuery();
            $this->end();
            stats('feedback.cancelled', 'feedback');

            return;
        }

        //get the input
        $feedback = $bot->message()?->text;

        //check valid input
        if (blank($feedback)) {
            $bot->sendMessage(message('feedback.wrong', vendor: 'bot-core'), [
                'parse_mode' => ParseMode::HTML,
            ]);
            $this->start($bot);

            return;
        }

        //save feedback
        Feedback::create([
            'chat_id' => $bot->userId(),
            'message' => $feedback,
        ]);

        //send feedback to dev
        $bot->sendMessage(message('feedback.received', [
            'from' => "{$bot->user()?->first_name} {$bot->user()?->last_name}",
            'username' => $bot->user()?->username,
            'user_id' => $bot->userId(),
            'message' => $feedback,
        ], 'bot-core'), [
            'chat_id' => config('bot-core.developer.id'),
        ]);

        $this->success = true;

        //close conversation
        $this->end();

        stats('feedback.sent', 'feedback');
    }

    public function closing(Nutgram $bot): void
    {
        $bot->deleteMessage($this->chat_id, $this->message_id);

        if ($this->success) {
            $bot->sendMessage(message('feedback.thanks', vendor: 'bot-core'));

            return;
        }

        $bot->sendMessage(message('feedback.cancelled', vendor: 'bot-core'));
    }
}
