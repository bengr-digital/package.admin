<?php

namespace Bengr\Admin\Tests\Unit\AdminManager;

use Bengr\Admin\Tests\TestCase;

class BuildingNavigationTest extends TestCase
{
    public function test_building_simple_navigation()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('ForNavigation/Simple'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\Simple'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this
            ->assertNavigationItemRegisteredCount(3)
            ->assertNavigationItemRegistered(
                label: 'First Page',
                iconName: 'description',
                iconType: 'outlined',
                routeName: 'admin.components.pages.first-page',
                routeUrl: '/first-page',
            )
            ->assertNavigationItemRegistered(
                label: 'Second Page',
                iconName: 'description',
                iconType: 'outlined',
                routeName: 'admin.components.pages.second-page',
                routeUrl: '/second-page',
            )
            ->assertNavigationItemRegistered(
                label: 'Third Page',
                iconName: 'description',
                iconType: 'outlined',
                routeName: 'admin.components.pages.third-page',
                routeUrl: '/third-page',
            );
    }

    public function test_building_nested_navigation_with_children()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('ForNavigation/Nested'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\Nested'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this
            ->assertNavigationItemRegisteredCount(3)
            ->assertNavigationItemRegistered(
                label: 'Third Page',
                iconName: 'description',
                iconType: 'outlined',
                routeName: 'admin.components.pages.third-page',
                routeUrl: '/third-page',
                children: [
                    [
                        'label' => 'Second Page',
                        'iconName' => 'description'
                    ]
                ]
            )
            ->assertNavigationItemRegistered(
                label: 'Second Page',
                iconName: 'description',
                iconType: 'outlined',
                routeName: 'admin.components.pages.second-page',
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
            'admin.components.pages.path' => $this->getTestPagePath('ForNavigation/ModifiedProperties'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\ModifiedProperties'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this
            ->assertNavigationItemRegisteredCount(1)
            ->assertNavigationItemRegistered(
                label: 'Testing navigation label',
                iconName: 'testing',
                iconType: 'filled',
                activeIconName: 'active_testing',
                activeIconType: 'outlined',
                badge: 12,
                badgeColor: 'red',
                routeUrl: '/testing/slug',
                routeName: 'admin.components.pages.testing.slug'
            );
    }

    public function test_building_navigation_with_excluded_pages()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('ForNavigation/WithExcluded'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\WithExcluded'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this
            ->assertNavigationItemRegisteredCount(1)
            ->assertNavigationItemRegistered(
                label: 'Third Page',
                routeUrl: '/third-page'
            );
    }

    public function test_building_navigation_with_nested_exluded_pages()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('ForNavigation/NestedWithExcluded'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('ForNavigation\\NestedWithExcluded'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this
            ->assertNavigationItemRegisteredCount(1)
            ->assertNavigationItemRegistered(
                label: 'Third Page',
                routeUrl: '/third-page',
            );
    }

    public function test_building_grouped_navigation()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Grouped'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Grouped'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this
            ->assertNavigationGroupRegisteredCount(2)
            ->assertNavigationGroupRegisteredItemsCount(null, 1)
            ->assertNavigationGroupRegistered(
                label: null,
                items: [
                    [
                        'label' => 'First Page',
                        'routeUrl' => '/first-page',
                    ]
                ]
            )
            ->assertNavigationGroupRegisteredItemsCount('testing', 2)
            ->assertNavigationGroupRegistered(
                label: 'testing',
                items: [
                    [
                        'label' => 'Third Page',
                        'routeUrl' => '/third-page',
                    ],
                    [
                        'label' => 'Second Page',
                        'routeUrl' => '/second-page',
                    ]
                ]
            );
    }

    public function test_building_grouped_navigation_with_nested_items()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('GroupedWithNested'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('GroupedWithNested'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this
            ->assertNavigationGroupRegisteredCount(3)
            ->assertNavigationGroupRegisteredItemsCount(null, 1)
            ->assertNavigationGroupRegisteredItemsCount('testing_first', 2)
            ->assertNavigationGroupRegisteredItemsCount('testing_second', 1)
            ->assertNavigationGroupRegistered(
                label: null,
                items: [
                    [
                        'label' => 'First Page',
                        'routeUrl' => '/first-page',
                    ]
                ]
            )
            ->assertNavigationGroupRegistered(
                label: 'testing_first',
                items: [
                    [
                        'label' => 'Second Page',
                        'routeUrl' => '/second-page',
                        'children' => [
                            [
                                'label' => 'Third Page',
                                'routeUrl' => '/third-page',
                            ]
                        ]
                    ],
                    [
                        'label' => 'Fourth Page',
                        'routeUrl' => '/fourth-page',
                    ]
                ]
            )
            ->assertNavigationGroupRegistered(
                label: 'testing_second',
                items: [
                    [
                        'label' => 'Fifth Page',
                        'routeUrl' => '/fifth-page',
                        'children' => [
                            [
                                'label' => 'Sixth Page',
                                'routeUrl' => '/sixth-page',
                            ]
                        ]
                    ]
                ]
            );
    }
}
