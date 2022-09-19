<?php

namespace OurNew\BotCore\Telegram\Commands;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;
use function OurNew\BotCore\Helpers\stats;

class CancelCommand
{
    public function __invoke(Nutgram $bot): void
    {
        try {
            //end conversation
            $bot->endConversation();
            
            //remove keyboard if exists and delete this message
            $bot->sendMessage('Removing keyboard...', [
                'reply_markup' => ReplyKeyboardRemove::make(true),
            ])?->delete();
        } finally {
            stats('cancel', 'command');
        }
    }
}