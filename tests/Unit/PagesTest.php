<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_registering_pages_from_path_and_from_register_property()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('Simple'),
            'admin.pages.namespace' => $this->getTestPageNamespace('Simple'),
            'admin.pages.register' => [
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
            'admin.pages.path' => $this->getTestPagePath('UnknownPath'),
            'admin.pages.namespace' => $this->getTestPageNamespace('UnknownPath'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(0);
    }

    public function test_registering_nested_pages()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('Nested'),
            'admin.pages.namespace' => $this->getTestPageNamespace('Nested'),
            'admin.pages.register' => []
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
            'admin.pages.path' => $this->getTestPagePath('WithoutExtendedPageClass'),
            'admin.pages.namespace' => $this->getTestPageNamespace('WithoutExtendedPageClass'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(0);
    }

    public function test_registering_pages_with_different_file_and_class_name()
    {
        config([
            'admin.pages.path' => $this->getTestPagePath('FileNameAndClassNameDiffers'),
            'admin.pages.namespace' => $this->getTestPageNamespace('FileNameAndClassNameDiffers'),
            'admin.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(0);
    }
}
