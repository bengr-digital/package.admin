<?php

use Bengr\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;

Route::prefix(Admin::getApiPrefix())
    ->middleware(Admin::getApiMiddleware())
    ->group(function () {
        foreach (Admin::getApiRoutes() as $route) {
            Route::match($route['method'] ?? 'get', $route['url'], $route['controller'])
                ->name($route['name'])
                ->middleware($route['middleware']);
        }
    });
