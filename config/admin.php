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

    'pages' => [
        'namespace' => 'App\\Admin\\Pages',
        'path' => app_path('Admin/Pages'),
        'register' => []
    ],
];
