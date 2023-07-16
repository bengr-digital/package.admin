<?php

namespace Bengr\Admin\Tests\Unit\Rules;

use Bengr\Admin\Rules\ValidWidgetId;
use Bengr\Admin\Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ValidWidgetIdTest extends TestCase
{
    public function test_valid_widget_id_with_existing_page_and_existing_widget()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Rules/ValidWidgetId'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Rules/ValidWidgetId'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $validator = Validator::make(
            [
                'url' => '/valid-widget-id',
                'widget_id' => 69
            ],
            [
                'widget_id' => [new ValidWidgetId]
            ]
        );

        $this->assertTrue($validator->passes());
    }
}
