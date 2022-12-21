<?php

return [
    'name' => null,

    'logo' => null,

    'logo_small' => null,

    'favicon' => null,

    'prefix' => 'admin',

    'prefix_name' => 'admin',

    'middleware' => ['api'],

    'auth' => [
        'guard' => 'admin',
        'routes' => [
            'login' => [
                'url' => '/auth/login',
                'name' => 'auth.login',
                'middleware' => ['guest:admin']
            ],
            'logout' => [
                'url' => '/auth/logout',
                'name' => 'auth.logout',
                'middleware' => ['auth:admin']
            ],
            'me' => [
                'url' => '/auth/me',
                'name' => 'auth.me',
                'middleware' => ['auth:admin']
            ],
            'token' => [
                'url' => '/auth/token',
                'name' => 'auth.token',
                'middleware' => []
            ]
        ]
    ],

    'builder' => [
        'url' => '/builder',
        'name' => 'builder',
        'middleware' => [
            Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class,
        ]
    ],

    'resources' => [
        'url' => '/resources',
        'name' => 'resources',
        'middleware' => []
    ],

    'pages' => [
        'namespace' => 'App\\Admin\\Pages',
        'path' => app_path('Admin/Pages'),
        'dashboard' => null,
        'login' => null,
        'register' => []
    ],

    'tables' => [
        'pagination' => [
            'per_page' => 5,
            'page_name' => 'page'
        ],
        'sorting' => [
            'params' => [
                'sort_column' => 'sort_column',
                'sort_order' => 'sort_order'
            ]
        ]
    ]
];
