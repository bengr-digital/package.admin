<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomProperties\Middleware;

use Bengr\Admin\Pages\Page;

class CustomPage extends Page
{
    protected string | array $middlewares = ['auth:admin', 'blabla'];
}
