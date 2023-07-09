<?php

namespace Bengr\Admin\Tests\Unit\AdminManager;

use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Bengr\Admin\Tests\Support\TestResources\Models\SubpageContent;
use Bengr\Admin\Tests\TestCase;

class ObtainingAndRegisteringPagesTest extends TestCase
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

    public function test_obtaining_registered_page_by_key()
    {
        config([
            'admin.components.pages.path' => null,
            'admin.components.pages.namespace' => null,
            'admin.components.pages.register' => [
                'dashboard' => \Bengr\Admin\Pages\Builtin\Dashboard::class,
                'login' => \Bengr\Admin\Pages\Builtin\Login::class,
            ]
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(2);

        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Dashboard::class);
        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Login::class);

        $this->assertEquals(app(\Bengr\Admin\Pages\Builtin\Dashboard::class), $this->adminManager->getPageByKey('dashboard'));
    }

    public function test_obtaining_registered_page_by_unknown_key()
    {
        config([
            'admin.components.pages.path' => null,
            'admin.components.pages.namespace' => null,
            'admin.components.pages.register' => [
                'dashboard' => \Bengr\Admin\Pages\Builtin\Dashboard::class,
                'login' => \Bengr\Admin\Pages\Builtin\Login::class,
            ]
        ]);

        $this->adminManager->registerComponents();

        $this->assertPageRegisteredCount(2);

        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Dashboard::class);
        $this->assertPageRegistered(\Bengr\Admin\Pages\Builtin\Login::class);

        $this->assertEquals(null, $this->adminManager->getPageByKey('random'));
    }

    public function test_obtaining_registered_page_with_plain_slug_by_plain_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/Plain'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/Plain'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\Plain::class, get_class($this->adminManager->getPageByUrl('plain')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\Plain::class, get_class($this->adminManager->getPageByUrl('/plain')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\Plain::class, get_class($this->adminManager->getPageByUrl('plain/')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\Plain::class, get_class($this->adminManager->getPageByUrl('/plain/')));
    }

    public function test_obtaining_registered_page_with_prefix_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/Plain'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/Plain'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefix::class, get_class($this->adminManager->getPageByUrl('plain/with-prefix')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefix::class, get_class($this->adminManager->getPageByUrl('/plain/with-prefix')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefix::class, get_class($this->adminManager->getPageByUrl('plain/with-prefix/')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefix::class, get_class($this->adminManager->getPageByUrl('/plain/with-prefix/')));
    }

    public function test_obtaining_registered_page_with_sufix_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/Plain'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/Plain'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithSufix::class, get_class($this->adminManager->getPageByUrl('plain/with-sufix')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithSufix::class, get_class($this->adminManager->getPageByUrl('/plain/with-sufix')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithSufix::class, get_class($this->adminManager->getPageByUrl('plain/with-sufix/')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithSufix::class, get_class($this->adminManager->getPageByUrl('/plain/with-sufix/')));
    }

    public function test_obtaining_registered_page_with_prefix_and_sufix_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/Plain'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/Plain'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefixAndSufix::class, get_class($this->adminManager->getPageByUrl('plain/with-prefix/and-sufix')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefixAndSufix::class, get_class($this->adminManager->getPageByUrl('/plain/with-prefix/and-sufix')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefixAndSufix::class, get_class($this->adminManager->getPageByUrl('plain/with-prefix/and-sufix/')));
        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain\PlainWithPrefixAndSufix::class, get_class($this->adminManager->getPageByUrl('/plain/with-prefix/and-sufix/')));
    }

    public function test_obtaining_registered_page_with_param_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        $subpage = Subpage::create([
            'id' => 1,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);
        $page = $this->adminManager->getPageByUrl('/subpages/1');

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithParam\WithParam::class, get_class($page));
        $this->assertEquals($subpage->id, $page->getProperty('subpage')->id);
    }

    public function test_obtaining_registered_page_with_param_without_property_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        $subpage = Subpage::create([
            'id' => 1,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);
        $page = $this->adminManager->getPageByUrl('/subpages/1/without-property');

        $this->assertEquals(null, $page);
    }

    public function test_obtaining_registered_page_with_param_with_column_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        $subpage = Subpage::create([
            'id' => 1,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);
        $page = $this->adminManager->getPageByUrl('/subpages/testing/with-column');

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithParam\WithParamWithColumn::class, get_class($page));
        $this->assertEquals($subpage->id, $page->getProperty('subpage')->id);
        $this->assertEquals($subpage->name_code, $page->getProperty('subpage')->name_code);
    }

    public function test_obtaining_registered_page_with_unknown_param_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithUnknownParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithUnknownParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $page = $this->adminManager->getPageByUrl('/subpages/random_unknown');

        $this->assertEquals(null, $page);
    }

    public function test_obtaining_registered_page_with_param_slug_by_url_on_no_pages_registered()
    {
        config([
            'admin.components.pages.path' => null,
            'admin.components.pages.namespace' => null,
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $page = $this->adminManager->getPageByUrl('/subpages/random_unknown');

        $this->assertEquals(null, $page);
    }

    public function test_obtaining_registered_page_with_soft_deleted_param_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithSoftDeletedParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithSoftDeletedParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        $subpage = Subpage::create([
            'id' => 1,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);
        $subpage->delete();
        $page = $this->adminManager->getPageByUrl('/subpages/1');

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithSoftDeletedParam\WithSoftDeletedParam::class, get_class($page));
        $this->assertEquals($subpage->id, $page->getProperty('subpage')->id);
    }

    public function test_obtaining_registered_page_with_nested_param_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithNestedParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithNestedParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        $subpage = Subpage::create([
            'id' => 1,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);
        $content = SubpageContent::create([
            'id' => 1,
            'subpage_id' => $subpage->id,
            'code' => 'claim:title',
            'text' => 'Claim title text as testing subpage content'
        ]);

        $page = $this->adminManager->getPageByUrl('/subpages/1/content/1');

        $this->assertEquals(\Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithNestedParam\WithNestedParam::class, get_class($page));
        $this->assertEquals($subpage->id, $page->getProperty('subpage')->id);
        $this->assertEquals($content->id, $page->getProperty('content')->id);
    }

    public function test_obtaining_registered_page_with_nested_without_relation_param_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithNestedParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithNestedParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        Subpage::create([
            'id' => 1,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);
        Subpage::create([
            'id' => 2,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);
        SubpageContent::create([
            'id' => 1,
            'subpage_id' => 2,
            'code' => 'claim:title',
            'text' => 'Claim title text as testing subpage content'
        ]);

        $page = $this->adminManager->getPageByUrl('/subpages/1/content/1');

        $this->assertEquals(null, $page);
    }

    public function test_obtaining_registered_page_with_nested_without_first_existing_param_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithNestedParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithNestedParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        SubpageContent::create([
            'id' => 1,
            'subpage_id' => 2,
            'code' => 'claim:title',
            'text' => 'Claim title text as testing subpage content'
        ]);

        $page = $this->adminManager->getPageByUrl('/subpages/1/content/1');

        $this->assertEquals(null, $page);
    }

    public function test_obtaining_registered_page_with_nested_without_second_existing_param_slug_by_url()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('CustomSlug/WithNestedParam'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('CustomSlug/WithNestedParam'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();
        Subpage::create([
            'id' => 1,
            'title' => 'Testing subpage',
            'description' => 'Testing subpage description',
            'keywords' => ['jedna', 'dva'],
            'path' => '/testing',
            'is_active' => true,
            'name_code' => 'testing'
        ]);

        $page = $this->adminManager->getPageByUrl('/subpages/1/content/1');

        $this->assertEquals(null, $page);
    }
}
