<?php

namespace Bengr\Admin\Tests\Unit\GlobalActions;

use Bengr\Admin\Tests\TestCase;

class CallingGlobalActionsTest extends TestCase
{
    public function test_calling_global_action()
    {
        config([
            'admin.components.global_actions.path' => $this->getTestGlobalActionPath('Calling'),
            'admin.components.global_actions.namespace' => $this->getTestGlobalActionNamespace('Calling'),
            'admin.components.global_actions.register' => []
        ]);

        $this->adminManager->registerComponents();

        $globalAction = $this->adminManager->getGlobalActionByName('calling-global-action');
        $response = $globalAction->call([]);

        $this->assertEquals('action was performed', $response);
    }
}
