<?php

namespace Bengr\Admin\Tests;

use Bengr\Admin\AdminManager;
use Bengr\Admin\AdminServiceProvider;
use Bengr\Admin\GlobalSearch\GlobalSearchResults;
use Bengr\Admin\Modals\Modal;
use Bengr\Admin\Navigation\NavigationGroup;
use Bengr\Admin\Navigation\NavigationItem;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected AdminManager $adminManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();

        Factory::guessFactoryNamesUsing(
            function (string $modelName) {
                return 'Bengr\\Admin\\Database\\Factories\\' . class_basename($modelName) . 'Factory';
            }
        );
        $this->adminManager = $this->app->make(AdminManager::class);
        $this->adminManager->registerHandler(app(ExceptionHandler::class));
    }

    protected function getPackageProviders($app)
    {
        $this->setUpConfig();

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

    protected function setUpDatabase()
    {
        Schema::create('subpages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description');
            $table->json('keywords');
            $table->json('path');
            $table->string('name_code');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('subpage_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subpage_id')->constrained('subpages', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('code');
            $table->json('text');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('password');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    protected function setUpConfig()
    {
        config();
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
        int $sort = null,
        int $order = null,
        array $navigationItems = null
    ): self {
        $navigationItems = !$navigationItems ? $this->getFlatNavigation() : collect($navigationItems);

        $naviagtionItemRegistered = collect($navigationItems)->contains(function (NavigationItem $navigationItem) use ($label, $iconName, $iconType, $activeIconName, $activeIconType, $group, $badge, $badgeColor, $routeName, $routeUrl, $children, $sort, $order) {
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

            if ($sort != null) {
                if ($sort != $navigationItem->getSort()) {
                    return false;
                }
            }

            if ($order != null) {
                if ($order != collect($this->adminManager->getNavigation()->first(fn ($group) => $group->getLabel() == $navigationItem->getGroup())->getItems())->search($navigationItem)) {
                    return false;
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

    public function assertNavigationGroupRegisteredCount(int $count): self
    {
        $navigationGroupRegisteredCount = count($this->adminManager->getNavigation());

        $this->assertEquals($count, $navigationGroupRegisteredCount);

        return $this;
    }

    public function assertNavigationGroupRegisteredItemsCount(?string $group, int $count): self
    {

        $navigationGroup = collect($this->adminManager->getNavigation())->first(fn ($navigationGroup) => $navigationGroup->getLabel() == $group);
        if ($navigationGroup) {
            $navigationGroupItemsCount = count($navigationGroup->getItems());
        } else {
            $navigationGroupItemsCount = 0;
        }

        $this->assertEquals($count, $navigationGroupItemsCount);

        return $this;
    }


    public function assertNavigationGroupRegistered(
        string $label = null,
        int $sort = null,
        int $order = null,
        array $items = null,
    ): self {

        $navigationGroupRegistered = collect($this->adminManager->getNavigation())->contains(function ($navigationGroup) use ($label, $items, $sort, $order) {
            if ($label != $navigationGroup->getLabel()) {
                return false;
            }

            if ($items != null) {
                foreach ($items as $item) {
                    $this->assertNavigationItemRegistered(
                        ...$item,
                        navigationItems: $navigationGroup->getItems()->toArray()
                    );
                }
            }

            if ($sort != null) {
                if ($sort != $navigationGroup->getSort()) {
                    return false;
                }
            }

            if ($order != null) {
                if ($order != collect($this->adminManager->getNavigation())->search($navigationGroup)) {
                    return false;
                }
            }

            return true;
        });

        $this->assertTrue($navigationGroupRegistered);

        return $this;
    }

    public function getFlatGlobalSearchResults($results)
    {
        $flatten = [];

        if ($results && $results instanceof GlobalSearchResults) {
            foreach ($results->getCategories()->toArray() as $category) {
                $flatten = array_merge($flatten, $category['results']);
            }
        }

        return $flatten;
    }

    public function assertGlobalSearchResultsCount(?GlobalSearchResults $results = null, int $count = 0): self
    {
        $actualCount = 0;

        if ($results instanceof GlobalSearchResults) {
            foreach ($results->getCategories() as $category) {
                $actualCount += count($category['results']);
            }
        }

        $this->assertEquals($count, $actualCount);

        return $this;
    }

    public function assertGlobalSearchResultExists(
        string $title = null,
        string $description = null,
        string $redirectName = null,
        string $redirectUrl = null,
        string $iconName = null,
        string $iconType = null,
        string $activeIconName = null,
        string $activeIconType = null,
        string $image = null,
        ?GlobalSearchResults $results = null
    ): self {

        $globalSearchResultExists = collect($this->getFlatGlobalSearchResults($results))->contains(function ($result) use ($title, $description, $redirectName, $redirectUrl, $iconName, $iconType, $activeIconName, $activeIconType, $image) {
            if ($title) {
                if ($title != $result['title']) {
                    return false;
                }
            }

            if ($description) {
                if ($description != $result['description']) {
                    return false;
                }
            }

            if ($redirectName) {
                if (is_array($result['redirect']) && array_key_exists('name', $result['redirect']) && $redirectName != $result['redirect']['name']) {
                    return false;
                } else if (!is_array($result['redirect'])) {
                    return false;
                } else if (is_array($result['redirect']) && !array_key_exists('name', $result['redirect'])) {
                    return false;
                }
            }

            if ($redirectUrl) {
                if (is_array($result['redirect']) && array_key_exists('url', $result['redirect']) && $redirectUrl != $result['redirect']['url']) {
                    return false;
                } else if (!is_array($result['redirect'])) {
                    return false;
                } else if (is_array($result['redirect']) && !array_key_exists('url', $result['redirect'])) {
                    return false;
                }
            }

            if ($iconName) {
                if (is_array($result['icon']) && array_key_exists('name', $result['icon']) && $iconName != $result['icon']['name']) {
                    return false;
                } else if (!is_array($result['icon'])) {
                    return false;
                } else if (is_array($result['icon']) && !array_key_exists('name', $result['icon'])) {
                    return false;
                }
            }

            if ($iconType) {
                if (is_array($result['icon']) && array_key_exists('type', $result['icon']) && $iconType != $result['icon']['type']) {
                    return false;
                } else if (!is_array($result['icon'])) {
                    return false;
                } else if (is_array($result['icon']) && !array_key_exists('type', $result['icon'])) {
                    return false;
                }
            }

            if ($activeIconName) {
                if (is_array($result['icon']) && array_key_exists('activeName', $result['icon']) && $iconName != $result['icon']['activeName']) {
                    return false;
                } else if (!is_array($result['icon'])) {
                    return false;
                } else if (is_array($result['icon']) && !array_key_exists('activeName', $result['icon'])) {
                    return false;
                }
            }

            if ($activeIconType) {
                if (is_array($result['icon']) && array_key_exists('type', $result['icon']) && $activeIconType != $result['icon']['type']) {
                    return false;
                } else if (!is_array($result['icon'])) {
                    return false;
                } else if (is_array($result['icon']) && !array_key_exists('type', $result['icon'])) {
                    return false;
                }
            }

            if ($image) {
                if ($image != $result['image']) {
                    return false;
                }
            }

            return true;
        });

        $this->assertTrue($globalSearchResultExists);

        return $this;
    }

    public function assertModalEquals(
        Modal $modal,
        ?int $id,
        ?string $codeId,
        string $type,
        string $direction,
        ?string $heading,
        ?string $subheading,
        array $widgets,
        array $actions,
        array $params,
        bool $hasCross,
        bool $lazyload
    ): self {
        $modalEquals = true;

        if ($modal->getId() != $id) {
            $modalEquals = false;
        }

        if ($modal->getCodeId() != $codeId) {
            $modalEquals = false;
        }

        if ($modal->getType() != $type) {
            $modalEquals = false;
        }

        if ($modal->getDirection() != $direction) {
            $modalEquals = false;
        }

        if ($modal->getHeading() != $heading) {
            $modalEquals = false;
        }

        if ($modal->getSubheading() != $subheading) {
            $modalEquals = false;
        }

        if ($modal->getWidgets() != $widgets) {
            $modalEquals = false;
        }

        if ($modal->getActions() != $actions) {
            $modalEquals = false;
        }

        if ($modal->getParams() != $params) {
            $modalEquals = false;
        }

        if ($modal->hasCross() != $hasCross) {
            $modalEquals = false;
        }

        if ($modal->getLazyload() != $lazyload) {
            $modalEquals = false;
        }

        $this->assertTrue($modalEquals);

        return $this;
    }

    public function getFlatWidgets(array $widgets): array
    {
        $flatten = [];
        $widgets = $widgets;

        foreach ($widgets as $widget) {
            $flatten[] = $widget;

            if ($widget->hasWidgets()) {
                $flatten = array_merge($flatten, $this->getFlatWidgets($widget->getWidgets()));
            }
        }

        return $flatten;
    }

    public function assertWidgetExists(
        array $widgets,
        string $class = null,
        ?int $widgetId,
    ): self {
        $widgetExists = collect($widgets)->contains(function ($widget) use ($class, $widgetId) {
            if ($class != null) {
                if ($class != get_class($widget)) {
                    return false;
                }
            }

            if ($widgetId != $widget->getWidgetId()) {
                return false;
            }

            return true;
        });

        $this->assertTrue($widgetExists);

        return $this;
    }

    public function assertContainsWidgets(array $expectedWidgets, array $actualWidgets): self
    {
        foreach ($expectedWidgets as $widget) {
            $this->assertWidgetExists(
                ...$widget,
                widgets: $this->getFlatWidgets($actualWidgets)
            );
        }

        return $this;
    }

    public function assertActionExists(
        array $actions,
        string $name,
        ?int $modalId,
        ?string $modalCodeId,
        string $modalEvent,
        mixed $handleMethodReturn,
        ?int $handleWidgetId,
        ?string $type
    ): self {
        $actionExists = collect($actions)->contains(function ($action) use ($name, $modalId, $modalCodeId, $modalEvent, $handleMethodReturn, $handleWidgetId, $type) {
            if ($name != null) {
                if ($name != $action->getName()) {
                    return false;
                }
            }

            if ($modalId != $action->getModalId()) {
                return false;
            }

            if ($modalCodeId != $action->getModalCodeId()) {
                return false;
            }

            if ($modalEvent != $action->getModalEvent()) {
                return false;
            }

            if ($handleMethodReturn == null && $action->getHandleMethod() != null) {
                return false;
            } else if ($handleMethodReturn != null && $handleMethodReturn != $action->getHandleMethod()()) {
                return false;
            }

            if ($handleWidgetId != $action->getHandleWidgetId()) {
                return false;
            }

            if ($type != $action->getType()) {
                return false;
            }

            return true;
        });

        $this->assertTrue($actionExists);

        return $this;
    }

    public function assertContainsActions(array $expectedActions, array $actualActions): self
    {
        foreach ($expectedActions as $action) {
            $this->assertActionExists(
                ...$action,
                actions: $actualActions
            );
        }

        return $this;
    }

    public function assertModalExists(
        array $modals,
        string $codeId,
        ?int $id
    ): self {
        $modalExists = collect($modals)->contains(function ($modal) use ($codeId, $id) {
            if ($codeId != $modal->getCodeId()) {
                return false;
            }

            if ($id != $modal->getId()) {
                return false;
            }

            return true;
        });

        $this->assertTrue($modalExists);

        return $this;
    }

    public function assertContainsModals(array $expectedModals, array $actualModals): self
    {
        foreach ($expectedModals as $modal) {
            $this->assertModalExists(
                ...$modal,
                modals: $actualModals
            );
        }

        return $this;
    }

    public function assertWidgetEquals(
        Widget $widget,
        ?int $id,
        ?string $name,
        int $columnSpan,
        int $sort,
        bool $lazyload
    ): self {
        $widgetEquals = true;

        if ($widget->getWidgetId() != $id) {
            $widgetEquals = false;
        }

        if ($widget->getWidgetName() != $name) {
            $widgetEquals = false;
        }

        if ($widget->getColumnSpan() != $columnSpan) {
            $widgetEquals = false;
        }

        if ($widget->getWidgetSort() != $sort) {
            $widgetEquals = false;
        }

        if ($widget->getLazyload() != $lazyload) {
            $widgetEquals = false;
        }

        $this->assertTrue($widgetEquals);

        return $this;
    }

    // $this->assertRouteRegistered(
    //     url: 'admin/builder/pages',
    //     name: 'admin.builder.pages',
    //     method: 'get',
    //     controller: [\Bengr\Admin\Http\Controllers\Builder\PageController::class, 'build'],
    //     middleware: ['api', Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class],
    // );
    public function assertRouteRegistered(
        string $url,
        string $name,
        string $method,
        array $controller,
        string | array $middleware,
    ): self {
        $method = collect($method);
        $middleware = collect($middleware);

        $routeRegistered = collect(Route::getRoutes()->getRoutes())->contains(function ($route) use ($url, $name, $method, $controller, $middleware) {

            if ($url != $route->uri()) {
                return false;
            }

            if ($name != $route->getName()) {
                return false;
            }

            foreach ($method as $method) {
                if (!collect($route->methods())->contains(Str::of($method)->upper())) {
                    return false;
                }
            }

            foreach ($middleware as $middleware) {
                if (!collect($route->getAction()['middleware'])->contains($middleware)) {
                    return false;
                }
            }

            if ($controller[0] . '@' . $controller[1] != $route->getActionName()) {
                return false;
            }

            return true;
        });

        $this->assertTrue($routeRegistered);

        return $this;
    }
}
