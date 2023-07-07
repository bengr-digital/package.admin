<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Tests\TestCase;

/**
 * What I have to test here
 * - navigation groups
 * - sorting of groups and other navigation items
 */
class NavigationTest extends TestCase
{
    public function test_building_simple_navigation()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('ForNavigation/Simple'),
            'admin.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\Simple'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertNavigationItemRegisteredCount(3);
        $this->assertNavigationItemRegistered(
            label: 'First Page',
            iconName: 'description',
            iconType: 'outlined',
            routeName: 'admin.pages.first-page',
            routeUrl: '/first-page',
        );
        $this->assertNavigationItemRegistered(
            label: 'Second Page',
            iconName: 'description',
            iconType: 'outlined',
            routeName: 'admin.pages.second-page',
            routeUrl: '/second-page',
        );
        $this->assertNavigationItemRegistered(
            label: 'Third Page',
            iconName: 'description',
            iconType: 'outlined',
            routeName: 'admin.pages.third-page',
            routeUrl: '/third-page',
        );
    }

    public function test_building_nested_navigation_with_children()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('ForNavigation/Nested'),
            'admin.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\Nested'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertNavigationItemRegisteredCount(3);
        $this->assertNavigationItemRegistered(
            label: 'Third Page',
            iconName: 'description',
            iconType: 'outlined',
            routeName: 'admin.pages.third-page',
            routeUrl: '/third-page',
            children: [
                [
                    'label' => 'Second Page',
                    'iconName' => 'description'
                ]
            ]
        );
        $this->assertNavigationItemRegistered(
            label: 'Second Page',
            iconName: 'description',
            iconType: 'outlined',
            routeName: 'admin.pages.second-page',
            routeUrl: '/second-page',
            children: [
                [
                    'label' => 'First Page',
                    'iconName' => 'description'
                ]
            ]
        );
    }

    public function test_building_navigation_with_modified_properties()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('ForNavigation/ModifiedProperties'),
            'admin.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\ModifiedProperties'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertNavigationItemRegisteredCount(1);
        $this->assertNavigationItemRegistered(
            label: 'Testing navigation label',
            iconName: 'testing',
            iconType: 'filled',
            activeIconName: 'active_testing',
            activeIconType: 'outlined',
            badge: 12,
            badgeColor: 'red',
            routeUrl: '/testing/slug',
            routeName: 'admin.pages.testing.slug'
        );
    }

    public function test_building_navigation_with_excluded_pages()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('ForNavigation/WithExcluded'),
            'admin.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\WithExcluded'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertNavigationItemRegisteredCount(1);
        $this->assertNavigationItemRegistered(
            label: 'Third Page',
            routeUrl: '/third-page'
        );
    }

    public function test_building_navigation_with_nested_exluded_pages()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('ForNavigation/NestedWithExcluded'),
            'admin.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\NestedWithExcluded'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertNavigationItemRegisteredCount(1);
        $this->assertNavigationItemRegistered(
            label: 'Third Page',
            routeUrl: '/third-page',
        );
    }

    public function test_building_grouped_navigation()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('ForNavigation/Grouped'),
            'admin.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\Grouped'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
    }
}
