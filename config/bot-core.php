<?php

use OurNew\BotCore\Telegram\Middlewares\CheckMaintenance;
use OurNew\BotCore\Telegram\Middlewares\CollectChat;

return [
    
    'middlewares' => [
        CollectChat::class,
        CheckMaintenance::class
    ],
    
    'commands' => [
        
        'privacy' => [
            'enabled' => false,
            'name' => 'privacy',
            'description' => 'Privacy Policy',
            'url' => env('BOTCORE_COMMANDS_PRIVACY_URL'),
        ]
    
    ]

];