<?php

namespace Bengr\Admin\Tests\Unit\Pages;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Pages\CustomProperties;
use Bengr\Admin\Tests\TestCase;

class PageTest extends TestCase
{
    public function test_layout_default_value()
    {
        $page = app(CustomProperties\Layout\DefaultPage::class);

        $this->assertEquals('app', $page->getLayout());
    }

    public function test_layout_custom_value()
    {
        $page = app(CustomProperties\Layout\CustomPage::class);

        $this->assertEquals('card', $page->getLayout());
    }

    public function test_title_default_value()
    {
        $page = app(CustomProperties\Title\DefaultPage::class);

        $this->assertEquals('Default Page', $page->getTitle());
    }

    public function test_title_custom_value()
    {
        $page = app(CustomProperties\Title\CustomPage::class);

        $this->assertEquals('Custom Title', $page->getTitle());
    }

    public function test_description_custom_value()
    {
        $page = app(CustomProperties\Description\CustomPage::class);

        $this->assertEquals('Custom Description Value', $page->getDescription());
    }

    public function test_slug_default_value()
    {
        $page = app(CustomProperties\Slug\DefaultPage::class);

        $this->assertEquals('default-page', $page->getSlug());
    }

    public function test_slug_custom_value()
    {
        $page = app(CustomProperties\Slug\CustomPage::class);

        $this->assertEquals('custom-slug', $page->getSlug());
    }

    public function test_parent_custom_value()
    {
        $page = app(CustomProperties\Parent\CustomPage::class);

        $this->assertInstanceOf(Page::class, app($page->getParent()));
    }

    public function test_middleware_custom_value()
    {
        $page = app(CustomProperties\Middleware\CustomPage::class);

        $this->assertEquals(['auth:admin', 'blabla'], $page->getMiddlewares());
    }

    public function test_has_navigation_default_value()
    {
        $page = app(CustomProperties\HasNavigation\DefaultPage::class);

        $this->assertTrue($page->hasNavigation());
    }

    public function test_has_navigation_custom_value()
    {
        $page = app(CustomProperties\HasNavigation\CustomPage::class);

        $this->assertFalse($page->hasNavigation());
    }

    public function test_has_topbar_default_value()
    {
        $page = app(CustomProperties\HasTopbar\DefaultPage::class);

        $this->assertTrue($page->hasTopbar());
    }

    public function test_has_topbar_custom_value()
    {
        $page = app(CustomProperties\HasTopbar\CustomPage::class);

        $this->assertFalse($page->hasTopbar());
    }

    public function test_breacrumbs_on_page_without_parent()
    {
        $page = app(CustomProperties\Breadcrumbs\NoParent::class);

        $this->assertEquals([], $page->getBreadcrumbs());
    }

    public function test_breacrumbs_on_page_with_parent()
    {
        $page = app(CustomProperties\Breadcrumbs\WithParent::class);

        $this->assertEquals([
            [
                'name' => 'Simple',
                'route' => [
                    'name' => 'admin.components.pages.simple',
                    'url' => '/simple'
                ]
            ],
            [
                'name' => 'With Parent',
                'route' => null
            ]
        ], $page->getBreadcrumbs());
    }

    public function test_breacrumbs_on_page_with_parent_with_parent()
    {
        $page = app(CustomProperties\Breadcrumbs\WithParentWithParent::class);

        $this->assertEquals([
            [
                'name' => 'Simple',
                'route' => [
                    'name' => 'admin.components.pages.simple',
                    'url' => '/simple'
                ]
            ],
            [
                'name' => 'With Parent',
                'route' => [
                    'name' => 'admin.components.pages.with-parent',
                    'url' => '/with-parent'
                ]
            ],
            [
                'name' => 'With Parent With Parent',
                'route' => null
            ]
        ], $page->getBreadcrumbs());
    }
}
