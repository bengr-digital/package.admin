<?php

namespace Bengr\Admin;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AdminServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('admin')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->app->scoped('admin', function () {
            return new AdminManager();
        });
    }

    public function packageBooted()
    {
    }
}
