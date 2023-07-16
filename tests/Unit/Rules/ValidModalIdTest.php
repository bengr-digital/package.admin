<?php

namespace Bengr\Admin\Tests\Unit\Rules;

use Bengr\Admin\Rules\ValidModalId;
use Bengr\Admin\Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ValidModalIdTest extends TestCase
{
    public function test_valid_widget_id_with_existing_page_and_existing_widget()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Rules/ValidModalId'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Rules/ValidModalId'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $validator = Validator::make(
            [
                'url' => '/valid-modal-id',
                'modal_id' => 69
            ],
            [
                'modal_id' => [new ValidModalId]
            ]
        );

        $this->assertTrue($validator->passes());
    }
}
