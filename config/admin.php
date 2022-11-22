<?php

return [
    'brand' => null,

    'logo' => null,

    'favicon' => null,

    'auth' => [
        'guard' => null,
        'model' => null,
        'expiration' => null,
    ],

    'pages' => [
        'namespace' => 'App\\Admin\\Pages',
        'path' => app_path('Admin/Pages'),
        'register' => []
    ],

    'resources' => [
        'namespace' => 'App\\Admin\\Resources',
        'path' => app_path('Admin/Resources'),
        'register' => []
    ],

    'widgets' => [
        'namespace' => 'App\\Admin\\Widgets',
        'path' => app_path('Admin/Widgets'),
        'register' => []
    ],

    'notifications' => []
];
