<?php

namespace OurNew\BotCore\Telegram\Commands;

use OurNew\BotCore\Concerns\StatsImplementation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use function OurNew\BotCore\Helpers\stats;

class StatsCommand
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage($this->getMessage(), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        stats('stats', 'command');
    }

    protected function getMessage(): string
    {
        $statsImplementation = app(StatsImplementation::class);

        return $statsImplementation->handle()->get();
    }
}
