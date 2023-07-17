<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Tests\TestCase;

class ApiTest extends TestCase
{
    protected function setUpConfig()
    {
        config([
            'auth.guards.admin' => [
                'driver' => 'token',
                'provider' => 'admins'
            ],
            'auth.providers.admins' => [
                'driver' => 'eloquent',
                'model' => \Bengr\Admin\Models\AdminUser::class
            ],
            'auth.tokens' => [
                'admins' => [
                    'expiration' => null,
                    'model' => \Bengr\Auth\Models\AuthToken::class
                ]
            ],
            'admin.api' => [
                'prefix' => 'admin/builder',
                'middleware' => ['api', \Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class],
                'routes' => [
                    'pages' => [
                        'url' => '/pages',
                        'method' => 'get',
                        'name' => 'admin.builder.pages',
                        'controller' => [\Bengr\Admin\Http\Controllers\Builder\PageController::class, 'build'],
                        'middleware' => [],
                    ],
                    'widgets' => [
                        'url' => '/widgets',
                        'method' => 'get',
                        'name' => 'admin.builder.widgets',
                        'controller' => [\Bengr\Admin\Http\Controllers\Builder\WidgetController::class, 'build'],
                        'middleware' => []
                    ],
                    'modals' => [
                        'url' => '/modals',
                        'method' => 'get',
                        'name' => 'admin.builder.modals',
                        'controller' => [\Bengr\Admin\Http\Controllers\Builder\ModalController::class, 'build'],
                        'middleware' => []
                    ],
                    'actions' => [
                        'url' => '/actions',
                        'method' => 'post',
                        'name' => 'admin.builder.actions',
                        'controller' => [\Bengr\Admin\Http\Controllers\Builder\ActionController::class, 'call'],
                        'middleware' => []
                    ],
                ]
            ]
        ]);
    }

    public function test_api_routes_where_registered_successfully()
    {
        $this->assertRouteRegistered(
            url: 'admin/builder/pages',
            name: 'admin.builder.pages',
            method: 'get',
            controller: [\Bengr\Admin\Http\Controllers\Builder\PageController::class, 'build'],
            middleware: ['api', \Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class],
        );

        $this->assertRouteRegistered(
            url: 'admin/builder/widgets',
            name: 'admin.builder.widgets',
            method: 'get',
            controller: [\Bengr\Admin\Http\Controllers\Builder\WidgetController::class, 'build'],
            middleware: ['api', \Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class],
        );

        $this->assertRouteRegistered(
            url: 'admin/builder/modals',
            name: 'admin.builder.modals',
            method: 'get',
            controller: [\Bengr\Admin\Http\Controllers\Builder\ModalController::class, 'build'],
            middleware: ['api', \Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class],
        );

        $this->assertRouteRegistered(
            url: 'admin/builder/actions',
            name: 'admin.builder.actions',
            method: 'post',
            controller: [\Bengr\Admin\Http\Controllers\Builder\ActionController::class, 'call'],
            middleware: ['api', \Bengr\Admin\Http\Middleware\DispatchServingAdminEvent::class],
        );
    }

    public function test_making_api_request_to_normal_page()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Simple'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Simple'),
            'admin.components.pages.register' => [],
        ]);

        $this->adminManager->registerComponents();

        $response = $this->get("admin/builder/pages?url=/simple");

        $response->assertStatus(200)
            ->assertJsonPath('title', 'Simple')
            ->assertJsonPath('description', '')
            ->assertJsonPath('route', [
                'name' => 'admin.components.pages.simple',
                'url' => '/simple'
            ])
            ->assertJsonPath('auth', null)
            ->assertJsonPath('breadcrumbs', [])
            ->assertJsonPath('layout.name', 'app')
            ->assertJsonPath('layout.topbar.userMenu.items', [])
            ->assertJsonPath('layout.topbar.notifications', null)
            ->assertJsonPath('layout.topbar.globalSearch', true)
            ->assertJsonPath('layout.header', [
                'heading' => 'Simple',
                'subheading' => '',
                'actions' => []
            ])
            ->assertJsonPath('content.modals', [])
            ->assertJsonPath('content.widgets', []);
    }

    public function test_making_api_request_to_uknown_page_with_registered_dashboard()
    {
        config([
            'admin.components.pages.path' => null,
            'admin.components.pages.namespace' => null,
            'admin.components.pages.register' => [
                'dashboard' => \Bengr\Admin\Pages\Builtin\Dashboard::class,
            ],
        ]);

        $this->adminManager->registerComponents();

        $response = $this->get('admin/builder/pages?url=/unknown');

        $response->assertStatus(404)
            ->assertJsonPath('message', 'admin.exceptions.page_notfound')
            ->assertJsonPath('redirect.url', '/')
            ->assertJsonPath('redirect.name', 'admin.components.pages.index');
    }

    public function test_making_api_request_to_uknown_page_without_registered_dashboard()
    {
        config([
            'admin.components.pages.path' => null,
            'admin.components.pages.namespace' => null,
            'admin.components.pages.register' => [],
        ]);

        $this->adminManager->registerComponents();

        $response = $this->get('admin/builder/pages?url=/unknown');

        $response->assertStatus(404)
            ->assertJsonPath('redirect', null);
    }

    public function test_making_api_request_to_auth_page_as_guest_with_registered_login()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Auth'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Auth'),
            'admin.components.pages.register' => [
                'login' => \Bengr\Admin\Pages\Builtin\Auth\Login::class,
            ],
        ]);

        $this->adminManager->registerComponents();

        $response = $this->get('admin/builder/pages?url=/auth');

        $response->assertStatus(401)
            ->assertJsonPath('redirect.url', '/auth/login')
            ->assertJsonPath('redirect.name', 'admin.components.pages.auth.login');
    }

    public function test_making_api_request_to_auth_page_as_guest_without_registered_login()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Auth'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Auth'),
            'admin.components.pages.register' => [],
        ]);

        $this->adminManager->registerComponents();

        $response = $this->get('admin/builder/pages?url=/auth');

        $response->assertStatus(401)
            ->assertJsonPath('redirect', null);
    }
}
