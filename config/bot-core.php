<?php

return [
    
    'commands' => [
        
        'privacy' => [
            'enabled' => env('BOTCORE_PRIVACY_ENABLED', false),
            'description' => env('BOTCORE_PRIVACY_DESCRIPTION', 'Privacy Policy'),
            'url' => env('BOTCORE_PRIVACY_URL'),
        ]
    
    ]

];