<?php

return [
    
    'commands' => [
        
        'privacy' => [
            'enabled' => env('BOTCORE_PRIVACY_ENABLED', false),
            'name' => env('BOTCORE_PRIVACY_NAME', 'privacy'),
            'description' => env('BOTCORE_PRIVACY_DESCRIPTION', 'Privacy Policy'),
            'url' => env('BOTCORE_PRIVACY_URL'),
        ]
    
    ]

];