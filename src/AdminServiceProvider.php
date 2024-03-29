<?php

namespace Bengr\Admin;

use Bengr\Admin\Commands\AdminPageListCommand;
use Bengr\Admin\Commands\MakeAdminPageCommand;
use Bengr\Admin\Commands\MakeAdminUserCommand;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Navigation\UserMenuItem;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AdminServiceProvider extends PackageServiceProvider
{
    protected array $pages = [];

    protected array $globalActions = [];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('admin')
            ->hasConfigFile(['admin', 'media-library', 'activitylog'])
            ->hasTranslations()
            ->hasCommands([
                MakeAdminUserCommand::class,
                MakeAdminPageCommand::class,
                AdminPageListCommand::class,
            ])
            ->hasMigrations([
                'create_admin_users_table',
                'create_admin_settings_table',
                'create_admin_settings_languages_table',
                'create_admin_settings_socials_table',
                'create_admin_settings_billings_table',
                'create_media_table',
                'create_activity_log_table',
                'create_auth_tokens_table',
            ])
            ->hasRoutes(['web']);
    }

    public function packageRegistered()
    {
        $this->app->scoped(AdminManager::class, function () {
            return new AdminManager();
        });

        $this->app->booting(function () {
            Admin::registerComponents();
        });
    }

    public function packageBooted()
    {
        $this->publishes([
            __DIR__ . '/../storage/responses/bengr_admin' => storage_path('responses/bengr_admin'),
        ], 'admin-response-files');

        Admin::onServing(function () {
            if (Admin::getPageByKey('me')) {
                Admin::registerUserMenuItems([
                    UserMenuItem::make()
                        ->label(__('admin::pages.me.title'))
                        ->icon('settings')
                        ->route(Admin::getPageByKey('me')->getRouteName(), Admin::getPageByKey('me')->getRouteUrl()),
                ]);
            }
        });
    }
}
