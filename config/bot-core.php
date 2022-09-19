<?php

use OurNew\BotCore\Telegram\Commands\CancelCommand;
use OurNew\BotCore\Telegram\Commands\PrivacyCommand;
use OurNew\BotCore\Telegram\Commands\StartCommand;
use OurNew\BotCore\Telegram\Conversations\FeedbackConversation;
use OurNew\BotCore\Telegram\Middlewares\CheckMaintenance;
use OurNew\BotCore\Telegram\Middlewares\CollectChat;

return [
    
    'middlewares' => [
        CollectChat::class,
        CheckMaintenance::class
    ],
    
    'commands' => [
    
        'start' => [
            'enabled' => true,
            'name' => 'start',
            'description' => 'Welcome message',
            'callable' => StartCommand::class,
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
    
        'cancel' => [
            'enabled' => true,
            'name' => 'cancel',
            'description' => 'Close a conversation or a keyboard',
            'callable' => CancelCommand::class,
        ],

    ],
    
    'developer' => [
        'id' => (int)env('BOTCORE_DEVELOPER_ID'),
        'name' => env('BOTCORE_DEVELOPER_NAME'),
        'username' => env('BOTCORE_DEVELOPER_USERNAME'),
        'email' => env('BOTCORE_DEVELOPER_EMAIL'),
        'website' => env('BOTCORE_DEVELOPER_WEBSITE'),
    ]

];