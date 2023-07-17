<?php

namespace Bengr\Admin\Tests\Unit\Rules;

use Bengr\Admin\Rules\ValidPageUrl;
use Bengr\Admin\Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ValidPageUrlTest extends TestCase
{
    public function test_valid_page_url_with_existing_page()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('Rules/ValidPageUrl'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('Rules/ValidPageUrl'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $validator = Validator::make(
            [
                'url' => 'valid-page-url',
                'url_with_prefix' => '/valid-page-url',
                'url_with_sufix' => 'valid-page-url/',
                'url_with_prefix_and_sufix' => '/valid-page-url/',
            ],
            [
                'url' => [new ValidPageUrl],
                'url_with_prefixrl' => [new ValidPageUrl],
                'url_with_sufix' => [new ValidPageUrl],
                'url_with_prefix_and_sufix' => [new ValidPageUrl],
            ]
        );

        $this->assertTrue($validator->passes());
    }
}
