<?php

return [
    'name' => env("APP_NAME", "FSUU DSAC"),
    'manifest' => [
        'name' => env('APP_NAME', 'FSUU DSAC'),
        'short_name' => env("APP_NAME", "FSUUDSAC"),
        'start_url' => '/',
        'background_color' => env("MIX_APP_BG_COLOR", "#ffffff"),
        'theme_color' => env("MIX_APP_THEME_COLOR", "#000000"),
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => env("MIX_APP_THEME_COLOR", "black"),
        'icons' => [
            '72x72' => [
                'path' => env('MIX_APP_ICON_72x72'),
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => env('MIX_APP_ICON_96x96'),
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => env('MIX_APP_ICON_128x128'),
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => env('MIX_APP_ICON_144x144'),
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => env('MIX_APP_ICON_152x152'),
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => env('MIX_APP_ICON_192x192'),
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => env('MIX_APP_ICON_384x384'),
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => env('MIX_APP_ICON_512x512'),
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => env('MIX_APP_ICON_640x1136'),
            '750x1334' => env('MIX_APP_ICON_750x1334'),
            '828x1792' => env('MIX_APP_ICON_828x1792'),
            '1125x2436' => env('MIX_APP_ICON_1125x2436'),
            '1242x2208' => env('MIX_APP_ICON_1242x2208'),
            '1242x2688' => env('MIX_APP_ICON_1242x2688'),
            '1536x2048' => env('MIX_APP_ICON_1536x2048'),
            '1668x2224' => env('MIX_APP_ICON_1668x2224'),
            '1668x2388' => env('MIX_APP_ICON_1668x2388'),
            '2048x2732' => env('MIX_APP_ICON_2048x2732'),
        ],
        'shortcuts' => [
            [
                'name' => env("APP_NAME", "FSUU DSAC"),
                'description' => env('MIX_APP_DESCRIPTION'),
                'url' => env('APP_URL'),
                'icons' => [
                    "src" => env('MIX_APP_ICON_72x72'),
                    "purpose" => "any"
                ]
            ],
            // [
            //     'name' => 'Shortcut Link 2',
            //     'description' => 'Shortcut Link 2 Description',
            //     'url' => '/shortcutlink2'
            // ]
        ],
        'custom' => []
    ]
];