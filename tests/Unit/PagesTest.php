<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_registering_pages_from_path_and_from_register_property()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Simple'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Simple'),
            'admin.components.pages.register' => [
                \Bengr\Admin\Pages\Builtin\Settings\Settings::class,
                \Bengr\Admin\Pages\Builtin\Auth\Login::class,
                \Bengr\Admin\Pages\Builtin\Auth\Me::class,
                \Bengr\Admin\Pages\Builtin\Dashboard::class,
            ]
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(5);

        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Settings\Settings::class);
        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Auth\Login::class);
        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Auth\Me::class);
        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Dashboard::class);
        $this->assertPageRegistered(\Bengr\Admin\Tests\Support\TestResources\Pages\Simple\Simple::class);
    }

    public function test_registering_pages_from_unknown_path()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('UnknownPath'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('UnknownPath'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(0);
    }

    public function test_registering_nested_pages()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Nested'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Nested'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(4);

        $this->assertPageRegistered(\Bengr\Admin\Tests\Support\TestResources\Pages\Nested\Dashboard::class);
        $this->assertPageRegistered(\Bengr\Admin\Tests\Support\TestResources\Pages\Nested\Admins\Index::class);
        $this->assertPageRegistered(\Bengr\Admin\Tests\Support\TestResources\Pages\Nested\Admins\Create::class);
        $this->assertPageRegistered(\Bengr\Admin\Tests\Support\TestResources\Pages\Nested\Admins\Edit::class);
    }

    public function test_registering_pages_without_extended_page_class()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('WithoutExtendedPageClass'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('WithoutExtendedPageClass'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(0);
    }

    public function test_registering_pages_with_different_file_and_class_name()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('FileNameAndClassNameDiffers'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('FileNameAndClassNameDiffers'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(0);
    }
}
