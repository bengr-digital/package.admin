<?php

use Bengr\Admin\Http\Controllers\Auth\AuthController;
use Bengr\Admin\Http\Controllers\Auth\MeController;
use Bengr\Admin\Http\Controllers\Builder\ActionController;
use Bengr\Admin\Http\Controllers\Builder\ModalController;
use Bengr\Admin\Http\Controllers\Builder\PageController;
use Bengr\Admin\Http\Controllers\Builder\WidgetController;
use Bengr\Admin\Http\Controllers\Settings\SettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('admin.routes.url'))
    ->name(config('admin.routes.name') . '.')
    ->middleware(config('admin.routes.middleware'))
    ->group(function () {
        Route::prefix(config('admin.routes.routes.auth.url'))
            ->name(config('admin.routes.routes.auth.name') . '.')
            ->middleware(config('admin.routes.routes.auth.middleware'))
            ->group(function () {

                Route::post(config('admin.routes.routes.auth.routes.login.url'), [AuthController::class, 'login'])
                    ->name(config('admin.routes.routes.auth.routes.login.name'))
                    ->middleware(config('admin.routes.routes.auth.routes.login.middleware'));

                Route::post(config('admin.routes.routes.auth.routes.logout.url'), [AuthController::class, 'logout'])
                    ->name(config('admin.routes.routes.auth.routes.logout.name'))
                    ->middleware(config('admin.routes.routes.auth.routes.logout.middleware'));

                Route::get(config('admin.routes.routes.auth.routes.token.url'), [AuthController::class, 'token'])
                    ->name(config('admin.routes.routes.auth.routes.token.name'))
                    ->middleware(config('admin.routes.routes.auth.routes.token.middleware'));

                Route::get(config('admin.routes.routes.auth.routes.me.url'), [MeController::class, 'me'])
                    ->name(config('admin.routes.routes.auth.routes.me.name'))
                    ->middleware(config('admin.routes.routes.auth.routes.me.middleware'));

                Route::put(config('admin.routes.routes.auth.routes.me.url'), [MeController::class, 'update'])
                    ->name(config('admin.routes.routes.auth.routes.me.name'))
                    ->middleware(config('admin.routes.routes.auth.routes.me.middleware'));

                Route::post(config('admin.routes.routes.auth.routes.me-avatar.url'), [MeController::class, 'uploadAvatar'])
                    ->name(config('admin.routes.routes.auth.routes.me-avatar.name'))
                    ->middleware(config('admin.routes.routes.auth.routes.me-avatar.middleware'));

                Route::delete(config('admin.routes.routes.auth.routes.me-avatar.url'), [MeController::class, 'deleteAvatar'])
                    ->name(config('admin.routes.routes.auth.routes.me-avatar.name'))
                    ->middleware(config('admin.routes.routes.auth.routes.me-avatar.middleware'));
            });

        Route::prefix(config('admin.routes.routes.settings.url'))
            ->name(config('admin.routes.routes.settings.name') . '.')
            ->middleware(config('admin.routes.routes.settings.middleware'))
            ->group(function () {
                Route::get(config('admin.routes.routes.settings.routes.settings.url'), [SettingsController::class, 'index'])
                    ->name(config('admin.routes.routes.settings.routes.settings.name'))
                    ->middleware(config('admin.routes.routes.settings.routes.settings.middleware'));

                Route::put(config('admin.routes.routes.settings.routes.settings.url'), [SettingsController::class, 'update'])
                    ->name(config('admin.routes.routes.settings.routes.settings.name'))
                    ->middleware(config('admin.routes.routes.settings.routes.settings.middleware'));

                Route::delete(config('admin.routes.routes.settings.routes.socials-delete.url'), [SettingsController::class, 'deleteSocial'])
                    ->name(config('admin.routes.routes.settings.routes.socials-delete.name'))
                    ->middleware(config('admin.routes.routes.settings.routes.socials-delete.middleware'));

                Route::delete(config('admin.routes.routes.settings.routes.languages-delete.url'), [SettingsController::class, 'deleteLanguage'])
                    ->name(config('admin.routes.routes.settings.routes.languages-delete.name'))
                    ->middleware(config('admin.routes.routes.settings.routes.languages-delete.middleware'));
            });

        Route::prefix(config('admin.routes.routes.builder.url'))
            ->name(config('admin.routes.routes.builder.name') . '.')
            ->middleware(config('admin.routes.routes.builder.middleware'))
            ->group(function () {
                Route::get(config('admin.routes.routes.builder.routes.pages.url'), [PageController::class, 'build'])
                    ->name(config('admin.routes.routes.builder.routes.pages.name'))
                    ->middleware(config('admin.routes.routes.builder.routes.pages.middleware'));

                Route::get(config('admin.routes.routes.builder.routes.widgets.url'), [WidgetController::class, 'build'])
                    ->name(config('admin.routes.routes.builder.routes.widgets.name'))
                    ->middleware(config('admin.routes.routes.builder.routes.widgets.middleware'));

                Route::post(config('admin.routes.routes.builder.routes.actions.url'), [ActionController::class, 'call'])
                    ->name(config('admin.routes.routes.builder.routes.actions.name'))
                    ->middleware(config('admin.routes.routes.builder.routes.actions.middleware'));

                Route::get(config('admin.routes.routes.builder.routes.modals.url'), [ModalController::class, 'build'])
                    ->name(config('admin.routes.routes.builder.routes.modals.name'))
                    ->middleware(config('admin.routes.routes.builder.routes.modals.middleware'));
            });
    });
