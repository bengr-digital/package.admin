<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Navigation\UserMenuItem;
use Bengr\Admin\Tests\TestCase;

class UserMenuTest extends TestCase
{
    public function test_registering_simple_user_menu_item()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make(),
            UserMenuItem::make(),
            UserMenuItem::make()
        ]);

        $this->assertUserMenuItemRegisteredCount(3);
    }

    public function test_registering_user_menu_item_with_label()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make()
                ->label('Custom Label')
        ]);

        $this->assertUserMenuItemRegisteredCount(1);

        $this->assertUserMenuItemregistered(
            label: 'Custom Label'
        );
    }

    public function test_registering_user_menu_item_with_icon()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make()
                ->icon('settings', 'filled')
        ]);

        $this->assertUserMenuItemRegisteredCount(1);

        $this->assertUserMenuItemregistered(
            iconName: 'settings',
            iconType: 'filled'
        );
    }

    public function test_registering_user_menu_item_with_icon_without_specified_type()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make()
                ->icon('settings')
        ]);

        $this->assertUserMenuItemRegisteredCount(1);

        $this->assertUserMenuItemregistered(
            iconName: 'settings',
            iconType: 'outlined'
        );
    }

    public function test_registering_user_menu_item_with_active_icon()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make()
                ->activeIcon('settings', 'filled')
        ]);

        $this->assertUserMenuItemRegisteredCount(1);

        $this->assertUserMenuItemregistered(
            activeIconName: 'settings',
            activeIconType: 'filled'
        );
    }

    public function test_registering_user_menu_item_with_active_icon_without_specified_type()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make()
                ->activeIcon('settings')
        ]);

        $this->assertUserMenuItemRegisteredCount(1);

        $this->assertUserMenuItemregistered(
            activeIconName: 'settings',
            activeIconType: 'outlined'
        );
    }

    public function test_registering_user_menu_item_with_route()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make()
                ->route('admin.components.pages.ahoj', '/ahoj')
        ]);

        $this->assertUserMenuItemRegisteredCount(1);

        $this->assertUserMenuItemregistered(
            routeName: 'admin.components.pages.ahoj',
            routeUrl: '/ahoj',
        );
    }

    public function test_registered_user_menu_items_are_sorted_correctly()
    {
        $this->adminManager->registerUserMenuItems([
            UserMenuItem::make()
                ->label('First Label')
                ->sort(2),
            UserMenuItem::make()
                ->label('Second Label')
                ->sort(0),
            UserMenuItem::make()
                ->label('Third Label')
                ->sort(1),
        ]);

        $this->assertUserMenuItemRegisteredCount(3);
        $this->assertUserMenuItemRegistered(
            label: 'First Label',
            sort: 2,
            order: 2
        );
        $this->assertUserMenuItemRegistered(
            label: 'Second Label',
            sort: 0,
            order: 0
        );
        $this->assertUserMenuItemRegistered(
            label: 'Third Label',
            sort: 1,
            order: 1
        );
    }
}
