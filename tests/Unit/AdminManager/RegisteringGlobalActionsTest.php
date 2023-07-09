<?php

namespace Bengr\Admin\Tests\Unit\AdminManager;

use Bengr\Admin\Tests\TestCase;

class RegisteringGlobalActionsTest extends TestCase
{
    public function test_registering_global_actions_from_path_and_from_register_property()
    {
        config([
            'admin.components.global_actions.path' => $this->getTestGlobalActionPath('Simple'),
            'admin.components.global_actions.namespace' => $this->getTestGlobalActionNamespace('Simple'),
            'admin.components.global_actions.register' => [
                \Bengr\Admin\GlobalActions\Builtin\GlobalSearch::class
            ]
        ]);

        $this->adminManager->registerComponents();

        $this->assertGlobalActionRegisteredCount(2);

        $this->assertGlobalActionRegistered(\Bengr\Admin\GlobalActions\Builtin\GlobalSearch::class);
        $this->assertGlobalActionRegistered(\Bengr\Admin\Tests\Support\TestResources\GlobalActions\Simple\Simple::class);
    }

    public function test_registering_global_actions_from_unknown_path()
    {
        config([
            'admin.components.global_actions.path' => $this->getTestGlobalActionPath('UnknownPath'),
            'admin.components.global_actions.namespace' => $this->getTestGlobalActionNamespace('UnknownPath'),
            'admin.components.global_actions.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertGlobalActionRegisteredCount(0);
    }

    public function test_registering_nested_global_actions()
    {
        config([
            'admin.components.global_actions.path' => $this->getTestGlobalActionPath('Nested'),
            'admin.components.global_actions.namespace' => $this->getTestGlobalActionNamespace('Nested'),
            'admin.components.global_actions.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertGlobalActionRegisteredCount(3);

        $this->assertGlobalActionRegistered(\Bengr\Admin\Tests\Support\TestResources\GlobalActions\Nested\GlobalSearch::class);
        $this->assertGlobalActionRegistered(\Bengr\Admin\Tests\Support\TestResources\GlobalActions\Nested\Auth\Logout::class);
        $this->assertGlobalActionRegistered(\Bengr\Admin\Tests\Support\TestResources\GlobalActions\Nested\Auth\RefreshToken::class);
    }
}
