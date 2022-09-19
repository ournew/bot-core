<?php

namespace OurNew\BotCore\Helpers;

use Illuminate\Support\Str;
use OurNew\BotCore\Models\Statistic;
use SergiX44\Nutgram\Nutgram;

/**
 * Render an HTML message
 * @param string $view
 * @param array $values
 * @return string
 */
function message(string $view, array $values = []): string
{
    return rescue(fn() => Str::of(view("bot-core::messages.$view", $values)->render())
        ->replaceMatches('/\r\n|\r|\n/', '')
        ->replace(['<br>', '</br>', '<BR>', '</BR>'], "\n")
        ->toString(), 'messages.' . $view, true);
}

/**
 * Save bot statistic
 * @param string $action
 * @param string|null $category
 * @param array|null $value
 * @param int|null $chat_id
 */
function stats(string $action, string $category = null, array $value = null, int $chat_id = null): void
{
    Statistic::create([
        'action' => $action,
        'category' => $category,
        'value' => $value,
        'chat_id' => $chat_id ?? app(Nutgram::class)->userId(),
    ]);
}