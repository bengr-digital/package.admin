<?php

return [
    'name' => null,

    'logo' => null,

    'logo_small' => null,

    'favicon' => null,

    'builder' => [
        'url' => '/admin/builder',
        'name' => 'admin.pages.builder',
        'middlewares' => ['api']
    ],

    'resources' => [
        'url' => '/admin/resources',
        'name' => 'admin.pages.resources',
        'middlewares' => ['api']
    ],

    'pages' => [
        'namespace' => 'App\\Admin\\Pages',
        'path' => app_path('Admin/Pages'),
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
