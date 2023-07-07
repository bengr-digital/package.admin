<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Name of the application
    |--------------------------------------------------------------------------
    |
    | This option describes name of the application that may be used further
    | inside of administration.
    | 
    */

    'name' => null,

    /*
    |--------------------------------------------------------------------------
    | Brand Logo
    |--------------------------------------------------------------------------
    |
    | Here you may specify path to the brand logo that will be displayed in
    | uncollapsed navigation bar of the side of administration.
    | 
    */

    'logo' => null,

    /*
    |--------------------------------------------------------------------------
    | Small Brand Logo
    |--------------------------------------------------------------------------
    |
    | Same as regular brand logo, but will be displayed in collapsed mode of
    | navigation bar.
    | 
    */

    'logo_small' => null,

    /*
    |--------------------------------------------------------------------------
    | Brand Favicon
    |--------------------------------------------------------------------------
    |
    | Here you may specify path to the brand favicon that will be displayed
    | in the icon of the browser tab.
    | 
    */

    'favicon' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Options
    |--------------------------------------------------------------------------
    |
    | This option describes authentication setup for authenticating admins
    | inside of administration.
    | 
    */

    'auth' => [
        'guard' => 'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | In context of this package component does not describe piece of UI but
    | rather a collection of functional objects that are used to describe
    | the UI or to describe some specific functionalities.
    |
    | In this option you may define components that will be registered
    | during process of booting the package.
    |
    | Supported Built-in Components: "pages", "global_actions"
    |
    */

    'components' => [
        'pages' => [
            'namespace' => 'App\\Admin\\Pages',
            'path' => app_path('Admin/Pages'),
            'register' => [
                'dashboard' => Bengr\Admin\Pages\Builtin\Dashboard::class,
                'login' => Bengr\Admin\Pages\Builtin\Auth\Login::class,
                'me' => Bengr\Admin\Pages\Builtin\Auth\Me::class,
                'settings' => Bengr\Admin\Pages\Builtin\Settings\Settings::class,
            ]
        ],
        'global_actions' => [
            'namespace' => 'App\\Admin\\GlobalActions',
            'path' => app_path('Admin/GlobalActions'),
            'register' => [
                Bengr\Admin\GlobalActions\Builtin\GlobalSearch::class,
            ]
        ],
    ],

    'pages' => [
        'namespace' => 'App\\Admin\\Pages',
        'path' => app_path('Admin/Pages'),
        'dashboard' => Bengr\Admin\Pages\Builtin\Dashboard::class,
        'login' => Bengr\Admin\Pages\Builtin\Auth\Login::class,
        'settings' => Bengr\Admin\Pages\Builtin\Settings\Settings::class,
        'me' => Bengr\Admin\Pages\Builtin\Auth\Me::class,
        'register' => [
            Bengr\Admin\Pages\Builtin\Settings\Settings::class,
            Bengr\Admin\Pages\Builtin\Auth\Login::class,
            Bengr\Admin\Pages\Builtin\Auth\Me::class,
            Bengr\Admin\Pages\Builtin\Dashboard::class,
        ]
    ],

    'global_actions' => [
        'namespace' => 'App\\Admin\\GlobalActions',
        'path' => app_path('Admin/GlobalActions'),
        'register' => [
            Bengr\Admin\GlobalActions\Builtin\GlobalSearch::class,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    | 
    | Visual of administration is build with the use of widgets. Each Widget
    | describes piece of UI component inside of the administration.
    |
    | In this option you may define specific configutaion options for some
    | of widgets. Some of them are built-in widgets, but you may also
    | create your own widgets and relly their configuration here.
    |
    | Supported Built-in Widgets: "table"
    | 
    */

    'widgets' => [
        'table' => [
            'pagination' => [
                'per_page' => 5,
                'page_name' => 'page'
            ],
            'sorting' => [
                'params' => [
                    'sort_column' => 'sort_by',
                    'sort_order' => 'sort_direction'
                ]
            ]
        ]
    ],

    'tables' => [
        'pagination' => [
            'per_page' => 5,
            'page_name' => 'page'
        ],
        'sorting' => [
            'params' => [
                'sort_column' => 'sort_by',
                'sort_order' => 'sort_direction'
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

    /*
    |--------------------------------------------------------------------------
    | Administration API 
    |--------------------------------------------------------------------------
    |
    | Administration provides an API for managing generation of pages, modals,
    | widgets, for calling actions and in future even more. This API is
    | crucial to provide this features to other applications.
    |
    | In this option you may specify basically anything about this awesome API. 
    | From name of the endpoints, middlewares, to controllers that are 
    | called by these endpoints. 
    | 
    */

    'api' => [
        'prefix' => '/admin/builder',
        'middleware' => ['api'],
        'routes' => [
            'pages' => [
                'url' => '/pages',
                'method' => 'get',
                'name' => 'admin.builder.pages',
                'controller' => [Bengr\Admin\Http\Controllers\Builder\PageController::class, 'build'],
                'middleware' => [],
            ],
            'widgets' => [
                'url' => '/widgets',
                'method' => 'get',
                'name' => 'admin.builder.widgets',
                'controller' => [Bengr\Admin\Http\Controllers\Builder\WidgetController::class, 'build'],
                'middleware' => []
            ],
            'modals' => [
                'url' => '/modals',
                'method' => 'get',
                'name' => 'admin.builder.modals',
                'controller' => [Bengr\Admin\Http\Controllers\Builder\ModalController::class, 'build'],
                'middleware' => []
            ],
            'actions' => [
                'url' => '/actions',
                'method' => 'post',
                'name' => 'admin.builder.actions',
                'controller' => [Bengr\Admin\Http\Controllers\Builder\ActionController::class, 'call'],
                'middleware' => []
            ],
        ]
    ]
];
