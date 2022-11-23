<?php

namespace Bengr\Admin;

use Bengr\Admin\Facades;
use Bengr\Admin\Pages\Page;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class AdminServiceProvider extends PackageServiceProvider
{
    protected array $pages = [];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('admin')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->app->resolving('admin', function () {
            Facades\Admin::registerPages($this->getPages());
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
    }

    public function registerComponents()
    {
        $this->pages = config('admin.pages.register') ?? [];

        $this->registerComponentsFromDirectory(
            Page::class,
            $this->pages,
            config('admin.pages.path'),
            config('admin.pages.namespace'),
        );
    }

    protected function registerComponentsFromDirectory(string $baseClass, array &$register, ?string $directory, ?string $namespace): void
    {
        if (blank($directory) || blank($namespace)) {
            return;
        }

        $filesystem = app(Filesystem::class);

        if ((!$filesystem->exists($directory)) && (!Str::of($directory)->contains('*'))) {
            return;
        }

        $namespace = Str::of($namespace);

        $register = array_merge(
            $register,
            collect($filesystem->allFiles($directory))
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
}
