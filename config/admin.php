<?php

return [
    'name' => null,

    'logo' => null,

    'logo_small' => null,

    'favicon' => null,

    'auth' => [
        'guard' => 'admin',
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
    ],

    'routes' => [
        'url' => '/admin',
        'name' => 'admin',
        'middleware' => ['api'],
        'routes' => [
            'auth' => [
                'url' => '/auth',
                'name' => 'auth',
                'middleware' => [],
                'routes' => [
                    'login' => [
                        'url' => '/login',
                        'name' => 'login',
                        'middleware' => ['guest:admin']
                    ],
                    'logout' => [
                        'url' => '/logout',
                        'name' => 'logout',
                        'middleware' => ['auth:admin']
                    ],
                    'me' => [
                        'url' => '/me',
                        'name' => 'me',
                        'middleware' => ['auth:admin']
                    ],
                    'me-avatar' => [
                        'url' => '/me/avatar',
                        'name' => 'me.avatar',
                        'middleware' => ['auth:admin']
                    ],
                    'token' => [
                        'url' => '/token',
                        'name' => 'token',
                        'middleware' => []
                    ]
                ]
            ],
            'settings' => [
                'url' => '/settings',
                'name' => 'settings',
                'middleware' => ['auth:admin'],
                'routes' => [
                    'settings' => [
                        'url' => '/',
                        'name' => 'index',
                        'middleware' => []
                    ],
                    'socials-delete' => [
                        'url' => '/socials/{id}',
                        'name' => 'socials.delete',
                        'middleware' => []
                    ],
                    'languages-delete' => [
                        'url' => '/languages/{id}',
                        'name' => 'languages.delete',
                        'middleware' => []
                    ]
                ]
            ],
            'builder' => [
                'url' => '/builder',
                'name' => 'builder',
                'middleware' => [
                    Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class,
                ],
                'routes' => [
                    'pages' => [
                        'url' => '/pages',
                        'name' => 'pages',
                        'middleware' => []
                    ],
                    'widgets' => [
                        'url' => '/widgets',
                        'name' => 'widgets',
                        'middleware' => []
                    ],
                    'actions' => [
                        'url' => '/actions',
                        'name' => 'actions',
                        'middleware' => []
                    ]
                ]
            ],
        ]
    ],
];
