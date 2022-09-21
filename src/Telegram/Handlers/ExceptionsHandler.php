<?php

namespace OurNew\BotCore\Telegram\Handlers;

use SergiX44\Nutgram\Nutgram;
use Throwable;
use function OurNew\BotCore\Helpers\message;

class ExceptionsHandler
{
    public function api(Nutgram $bot, Throwable $e): void
    {
        $this->reportException($bot, $e);
    }

    public function global(Nutgram $bot, Throwable $e): void
    {
        $mutedExceptions = collect(config('bot-core.exceptions.mute'));
        if ($mutedExceptions->contains(fn ($exception) => $e instanceof $exception)) {
            return;
        }

        $this->reportException($bot, $e);
    }

    public function reportException(Nutgram $bot, Throwable $e): void
    {
        report($e);

        if (!config('bot-core.exceptions.send')) {
            return;
        }

        $bot->sendMessage(message('about', [
            'name' => class_basename($e::class),
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => str($e->getFile())->replace(base_path(), ''),
        ], 'bot-core'), [
            'chat_id' => config('bot-core.developer.id'),
        ]);
    }
}
