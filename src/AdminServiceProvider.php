<?php

namespace Bengr\Admin;

use Bengr\Admin\Commands\AdminPageListCommand;
use Bengr\Admin\Commands\MakeAdminPageCommand;
use Bengr\Admin\Commands\MakeAdminUserCommand;
use Bengr\Admin\Facades;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\GlobalActions\GlobalAction;
use Bengr\Admin\Navigation\UserMenuItem;
use Bengr\Admin\Pages\Page;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class AdminServiceProvider extends PackageServiceProvider
{
    protected array $pages = [];
    protected array $globalActions = [];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('admin')
            ->hasConfigFile()
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
            ])
            ->hasRoutes(['web']);
    }

    public function packageRegistered()
    {
        $this->app->resolving('admin', function () {
            Facades\Admin::registerPages($this->getPages());
            Facades\Admin::registerGlobalActions($this->getGlobalActions());
        });

        $this->app->booting(function () {
            $this->registerComponents();
        });

        $this->app->scoped('admin', function () {
            return new AdminManager();
        });
    }

    public function packageBooted()
    {
        $this->publishes([
            __DIR__ . '/../storage/responses/bengr_admin' => storage_path('responses/bengr_admin'),
        ], 'admin-response-files');

        Admin::serving(function () {
            Admin::registerUserMenuItems([
                UserMenuItem::make()
                    ->label(__('admin::pages.me.title'))
                    ->icon('settings')
                    ->route(app(config('admin.pages.me'))->getRouteName(), app(config('admin.pages.me'))->getRouteUrl()),
            ]);
        });
    }

    public function registerComponents()
    {
        $this->pages = config('admin.pages.register') ?? [];
        $this->globalActions = config('admin.global_actions.register') ?? [];

        $this->registerComponentsFromDirectory(
            Page::class,
            $this->pages,
            config('admin.pages.path'),
            config('admin.pages.namespace'),
        );
        $this->registerComponentsFromDirectory(
            GlobalAction::class,
            $this->globalActions,
            config('admin.global_actions.path'),
            config('admin.global_actions.namespace'),
        );
    }

    protected function registerComponentsFromDirectory(string $baseClass, array &$register, ?string $directory, ?string $namespace): void
    {
        if (blank($directory) || blank($namespace)) {
            return;
        }

        $filesystem = app(Filesystem::class);
        $files = [];

        if ($filesystem->exists($directory)) {
            $files = $filesystem->allFiles($directory);
        }

        $namespace = Str::of($namespace);

        $register = array_merge(
            $register,
            collect($files)
                ->map(function (SplFileInfo $file) use ($namespace): string {
                    $variableNamespace = $namespace->contains('*') ? str_ireplace(
                        ['\\' . $namespace->before('*'), $namespace->after('*')],
                        ['', ''],
                        Str::of($file->getPath())
                            ->after(base_path())
                            ->replace(['/'], ['\\']),
                    ) : null;

                    if (is_string($variableNamespace)) {
                        $variableNamespace = (string) Str::of($variableNamespace)->before('\\');
                    }

                    return (string) $namespace
                        ->append('\\', $file->getRelativePathname())
                        ->replace('*', $variableNamespace)
                        ->replace(['/', '.php'], ['\\', '']);
                })
                ->filter(fn (string $class): bool => is_subclass_of($class, $baseClass) && (!(new \ReflectionClass($class))->isAbstract()))
                ->all(),
        );
    }

    protected function getPages(): array
    {
        return $this->pages;
    }

    protected function getGlobalActions(): array
    {
        return $this->globalActions;
    }
}
