<?php

namespace OurNew\BotCore\Telegram\Commands;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use function OurNew\BotCore\Helpers\message;
use function OurNew\BotCore\Helpers\stats;

class StartCommand
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage(message('start', vendor: 'bot-core'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        stats('start', 'command');
    }
}
