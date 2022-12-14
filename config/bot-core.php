<?php

use OurNew\BotCore\Telegram\Commands\AboutCommand;
use OurNew\BotCore\Telegram\Commands\CancelCommand;
use OurNew\BotCore\Telegram\Commands\HelpCommand;
use OurNew\BotCore\Telegram\Commands\PrivacyCommand;
use OurNew\BotCore\Telegram\Commands\StartCommand;
use OurNew\BotCore\Telegram\Commands\StatsCommand;
use OurNew\BotCore\Telegram\Conversations\FeedbackConversation;
use OurNew\BotCore\Telegram\Middlewares\CheckMaintenance;
use OurNew\BotCore\Telegram\Middlewares\CollectChat;

return [

    'info' => [
        'name' => env('APP_NAME'),
        'username' => env('BOTCORE_INFO_USERNAME'),
        'version' => 'v1.0',
        'source' => env('BOTCORE_INFO_SOURCE'),
        'changelog' => env('BOTCORE_INFO_CHANGELOG'),
    ],

    'developer' => [
        'id' => (int)env('BOTCORE_DEVELOPER_ID'),
        'name' => env('BOTCORE_DEVELOPER_NAME'),
        'username' => env('BOTCORE_DEVELOPER_USERNAME'),
        'email' => env('BOTCORE_DEVELOPER_EMAIL'),
        'website' => env('BOTCORE_DEVELOPER_WEBSITE'),
    ],

    'middlewares' => [
        CollectChat::class,
        CheckMaintenance::class,
    ],

    'commands' => [

        'start' => [
            'enabled' => true,
            'name' => 'start',
            'description' => 'Welcome message',
            'callable' => StartCommand::class,
        ],

        'help' => [
            'enabled' => true,
            'name' => 'help',
            'description' => 'Help message',
            'callable' => HelpCommand::class,
        ],

        'feedback' => [
            'enabled' => true,
            'name' => 'feedback',
            'description' => 'Send a feedback about the bot',
            'callable' => FeedbackConversation::class,
        ],

        'privacy' => [
            'enabled' => true,
            'name' => 'privacy',
            'description' => 'Privacy Policy',
            'callable' => PrivacyCommand::class,
            'url' => env('BOTCORE_COMMANDS_PRIVACY_URL'),
        ],

        'stats' => [
            'enabled' => true,
            'name' => 'stats',
            'description' => 'Show bot statistics',
            'callable' => StatsCommand::class,
            'updater' => [
                'implementation' => null,
                'cron' => '*/5 * * * *',
            ],
        ],

        'about' => [
            'enabled' => true,
            'name' => 'about',
            'description' => 'About the bot',
            'callable' => AboutCommand::class,
        ],

        'cancel' => [
            'enabled' => true,
            'name' => 'cancel',
            'description' => 'Close a conversation or a keyboard',
            'callable' => CancelCommand::class,
        ],

    ],

    'exceptions' => [
        'throw' => [
            '.*bot was blocked by the user.*' => OurNew\BotCore\Telegram\Exceptions\UserBlockedException::class,
            '.*user is deactivated.*' => OurNew\BotCore\Telegram\Exceptions\UserDeactivatedException::class,
        ],
        'mute' => [
            OurNew\BotCore\Telegram\Exceptions\UserBlockedException::class,
            OurNew\BotCore\Telegram\Exceptions\UserDeactivatedException::class,
        ],
        'send' => env('BOTCORE_EXCEPTIONS_SEND', true),
    ],

];
