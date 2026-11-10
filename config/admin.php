<?php

return [
    'panel_domain' => env('ADMIN_PANEL_DOMAIN', 'admin.localhost'),
    'accounts' => [
        'content' => [
            'name' => env('ADMIN_CONTENT_NAME', 'Administrator Konten'),
            'email' => env('ADMIN_CONTENT_EMAIL'),
            'password' => env('ADMIN_CONTENT_PASSWORD'),
            'role' => 'Admin Konten',
        ],
        'data' => [
            'name' => env('ADMIN_DATA_NAME', 'Administrator Data'),
            'email' => env('ADMIN_DATA_EMAIL'),
            'password' => env('ADMIN_DATA_PASSWORD'),
            'role' => 'Admin Data',
        ],
    ],
];
