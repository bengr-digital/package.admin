<?php

use Bengr\Admin\Http\Controllers\AuthController;
use Bengr\Admin\Http\Controllers\BuilderController;
use Bengr\Admin\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('admin.prefix'))
    ->middleware(config('admin.middleware'))
    ->name(config('admin.prefix_name') . '.')
    ->group(function () {
        Route::get(config('admin.builder.url'), BuilderController::class)
            ->name(config('admin.builder.name'))
            ->middleware(config('admin.builder.middleware'));

        Route::get(config('admin.resources.url'), [ResourceController::class, 'get'])
            ->name(config('admin.resources.name'))
            ->middleware(config('admin.resources.middleware'));

        Route::post(config('admin.auth.routes.login.url'), [AuthController::class, 'login'])
            ->name(config('admin.auth.routes.login.name'))
            ->middleware(config('admin.auth.routes.login.middleware'));

        Route::post(config('admin.auth.routes.logout.url'), [AuthController::class, 'logout'])
            ->name(config('admin.auth.routes.logout.name'))
            ->middleware(config('admin.auth.routes.logout.middleware'));

        Route::get(config('admin.auth.routes.me.url'), [AuthController::class, 'me'])
            ->name(config('admin.auth.routes.me.name'))
            ->middleware(config('admin.auth.routes.me.middleware'));

        Route::get(config('admin.auth.routes.token.url'), [AuthController::class, 'token'])
            ->name(config('admin.auth.routes.token.name'))
            ->middleware(config('admin.auth.routes.token.middleware'));
    });
