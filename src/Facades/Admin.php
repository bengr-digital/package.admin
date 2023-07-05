<?php

namespace Bengr\Admin\Facades;

use Bengr\Admin\AdminManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Illuminate\Contracts\Auth\Guard auth()
 * @method static string getGuardName()
 * @method static string authUserModel()
 * @method static string prefix()
 * @method static Bengr\Admin\Pages\Page loginPage()
 * @method static Bengr\Admin\Pages\Page dashboardPage()
 * @method static string authTokenModel()
 * @method static void registerComponents()
 * @method static void registerComponentsFromDirectory(string $baseClass, array &$register, ?string $directory, ?string $namespace)
 *
 * @see \Bengr\Admin\AdminManager
 */
class Admin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdminManager::class;
    }
}
