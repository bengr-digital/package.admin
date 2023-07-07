<?php

namespace Bengr\Admin\Tests;

use Bengr\Admin\AdminManager;
use Bengr\Admin\AdminServiceProvider;
use Bengr\Admin\Navigation\NavigationGroup;
use Bengr\Admin\Navigation\NavigationItem;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| What I need to test in this package
|--------------------------------------------------------------------------
| ---------
| | Pages |
| ---------
| - [x] test registering pages
| - [] test obtaining page by url, name, and others
| - [] test obtaining page via API, and test that it gives desired response
| --------------
| | Navigation |
| --------------
| - [x] test building navigation tree
| - [x] test modifing various properties
| - [x] test excluding pages
| - [x] testing nested navigation items
| - [] test groups
| - [] test sorting
| --------------
| | User Menu  |
| --------------
| - [x] test building user menu
| - [x] test adding items to user menu
| - [x] test modifing various types of properties
| - [x] test sorting the user items
| -----------
| | Widgets |
| -----------
| -----------
| | Actions |
| -----------
| ------------------
| | Global Actions |
| ------------------
| - [x] test registering global actions
| - [] test performing global actions
| - [] test performing global actions via API
| ----------
| | API    |
| ----------
| - [] test that all api routes are registered correctly
| - [] test making various requests to these routes and test that they give desired response
*/

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected AdminManager $adminManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminManager = $this->app->make(AdminManager::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            AdminServiceProvider::class,
        ];
    }

    public function getTestPath(string $directory = null): string
    {
        return realpath(__DIR__ . '/' . $directory);
    }

    public function getTestPagePath(string $directory): string
    {
        return $this->getTestPath('Support/TestResources/Pages/' . $directory);
    }

    public function getTestPageNamespace(string $directory): string
    {
        return $this->getTestNamespace() . 'Support\\TestResources\\Pages\\' . $directory;
    }

    public function getTestGlobalActionPath(string $directory): string
    {
        return $this->getTestPath('Support/TestResources/GlobalActions/' . $directory);
    }

    public function getTestGlobalActionNamespace(string $directory): string
    {
        return $this->getTestNamespace() . 'Support\\TestResources\\GlobalActions\\' . $directory;
    }


    public function getTestNamespace(): string
    {
        return 'Bengr\\Admin\\Tests\\';
    }

    public function assertPageRegistered(string $page): self
    {
        $pageRegistered = collect($this->adminManager->getPages())
            ->contains(fn ($adminManagerPage) => $adminManagerPage == $page);

        $this->assertTrue($pageRegistered);

        return $this;
    }

    public function assertPageRegisteredCount(int $count): self
    {
        $pageRegisteredCount = count($this->adminManager->getPages());

        $this->assertEquals($count, $pageRegisteredCount);

        return $this;
    }

    public function assertGlobalActionRegistered(string $globalAction): self
    {
        $globalActionRegistered = collect($this->adminManager->getGlobalActions())
            ->contains(fn ($adminManagerGlobalAction) => $adminManagerGlobalAction == $globalAction);

        $this->assertTrue($globalActionRegistered);

        return $this;
    }

    public function assertGlobalActionRegisteredCount(int $count): self
    {
        $globalActionRegisteredCount = count($this->adminManager->getGlobalActions());

        $this->assertEquals($count, $globalActionRegisteredCount);

        return $this;
    }

    public function assertNavigationItemRegisteredCount(int $count): self
    {
        $navigationItemRegisteredCount = count($this->getFlatNavigation());

        $this->assertEquals($count, $navigationItemRegisteredCount);

        return $this;
    }

    public function getFlatNavigation(Collection $items = null): array
    {
        $flatten = [];
        $items = $items ? $items->toArray() : $this->adminManager->getNavigation()->toArray();

        foreach ($items as $item) {
            if ($item instanceof NavigationGroup) {
                $flatten = array_merge($flatten, $this->getFlatNavigation($item->getItems()));
            } else if ($item instanceof NavigationItem) {

                if (count($item->getChildren())) {
                    $flatten = array_merge($flatten, $this->getFlatNavigation(collect($item->getChildren())));
                }

                $flatten[] = $item;
            }
        }

        $flatten = collect($flatten)->filter(function ($item, $index) use ($flatten) {
            if (collect($flatten)->contains(fn ($flattenItem) => $flattenItem->getLabel() == $item->getLabel() && count($flattenItem->getChildren()) > count($item->getChildren()))) {
                return false;
            }

            return true;
        })->toArray();

        return $flatten;
    }

    public function assertNavigationItemRegistered(
        string $label = null,
        string $iconName = null,
        string $iconType = null,
        string $activeIconName = null,
        string $activeIconType = null,
        string $group = null,
        string $badge = null,
        string $badgeColor = null,
        string $routeName = null,
        string $routeUrl = null,
        array $children = null,
        array $navigationItems = null
    ): self {
        $navigationItems = !$navigationItems ? $this->getFlatNavigation() : collect($navigationItems);

        $naviagtionItemRegistered = collect($navigationItems)->contains(function (NavigationItem $navigationItem) use ($label, $iconName, $iconType, $activeIconName, $activeIconType, $group, $badge, $badgeColor, $routeName, $routeUrl, $children) {
            if ($label != null) {
                if ($label != $navigationItem->getLabel()) {
                    return false;
                }
            }

            if ($iconName != null) {
                if ($iconName != $navigationItem->getIconName()) {
                    return false;
                }
            }

            if ($iconType != null) {
                if ($iconType != $navigationItem->getIconType()) {
                    return false;
                }
            }

            if ($activeIconName != null) {
                if ($activeIconName != $navigationItem->getActiveIconName()) {
                    return false;
                }
            }

            if ($activeIconType != null) {
                if ($activeIconType != $navigationItem->getActiveIconType()) {
                    return false;
                }
            }

            if ($group != null) {
                if ($group != $navigationItem->getGroup()) {
                    return false;
                }
            }

            if ($badge != null) {
                if ($badge != $navigationItem->getBadge()) {
                    return false;
                }
            }

            if ($badgeColor != null) {
                if ($badgeColor != $navigationItem->getBadgeColor()) {
                    return false;
                }
            }

            if ($routeName != null) {
                if ($routeName != $navigationItem->getRouteName()) {
                    return false;
                }
            }

            if ($routeUrl != null) {
                if ($routeUrl != $navigationItem->getRouteUrl()) {
                    return false;
                }
            }

            if ($children != null) {
                foreach ($children as $child) {
                    $this->assertNavigationItemRegistered(
                        ...$child,
                        navigationItems: $navigationItem->getChildren()
                    );
                }
            }

            return true;
        });

        $this->assertTrue($naviagtionItemRegistered);

        return $this;
    }

    public function assertUserMenuItemRegisteredCount(int $count): self
    {
        $userMenuItemsRegisteredCount = count($this->adminManager->getUserMenuItems());

        $this->assertEquals($count, $userMenuItemsRegisteredCount);

        return $this;
    }

    public function assertUserMenuItemregistered(
        string $label = null,
        string $iconName = null,
        string $iconType = null,
        string $activeIconName = null,
        string $activeIconType = null,
        string $routeName = null,
        string $routeUrl = null,
        int $sort = null,
        int $order = null,
    ): self {
        $userMenuItemRegistered = collect($this->adminManager->getUserMenuItems())->contains(function ($userMenuItem) use ($label, $iconName, $iconType, $activeIconName, $activeIconType, $routeName, $routeUrl, $sort, $order) {
            if ($label != null) {
                if ($label != $userMenuItem->getLabel()) {
                    return false;
                }
            }

            if ($iconName != null) {
                if ($iconName != $userMenuItem->getIconName()) {
                    return false;
                }
            }

            if ($iconType != null) {
                if ($iconType != $userMenuItem->getIconType()) {
                    return false;
                }
            }

            if ($activeIconName != null) {
                if ($activeIconName != $userMenuItem->getActiveIconName()) {
                    return false;
                }
            }

            if ($activeIconType != null) {
                if ($activeIconType != $userMenuItem->getActiveIconType()) {
                    return false;
                }
            }

            if ($routeName != null) {
                if ($routeName != $userMenuItem->getRouteName()) {
                    return false;
                }
            }

            if ($routeUrl != null) {
                if ($routeUrl != $userMenuItem->getRouteUrl()) {
                    return false;
                }
            }

            if ($sort != null) {
                if ($sort != $userMenuItem->getSort()) {
                    return false;
                }
            }

            if ($order != null) {
                if ($order != collect($this->adminManager->getUserMenuItems())->search($userMenuItem)) {
                    return false;
                }
            }

            return true;
        });

        $this->assertTrue($userMenuItemRegistered);

        return $this;
    }
}
