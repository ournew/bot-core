<?php

use OurNew\BotCore\Telegram\Commands\CancelCommand;
use OurNew\BotCore\Telegram\Commands\PrivacyCommand;
use OurNew\BotCore\Telegram\Middlewares\CheckMaintenance;
use OurNew\BotCore\Telegram\Middlewares\CollectChat;

return [
    
    'middlewares' => [
        CollectChat::class,
        CheckMaintenance::class
    ],
    
    'commands' => [
    
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

    ]

];