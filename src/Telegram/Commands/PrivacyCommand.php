<?php

namespace OurNew\BotCore\Telegram\Commands;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use function OurNew\BotCore\Helpers\message;
use function OurNew\BotCore\Helpers\stats;

class PrivacyCommand
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage(message('privacy', vendor: 'bot-core'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
            'reply_markup' => InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make(
                    text: trans('bot-core::privacy.title'),
                    url: config('bot-core.commands.privacy.url')
                )),
        ]);

        stats('privacy', 'command');
    }
}
