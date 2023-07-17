<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\Auth;

use Bengr\Admin\Pages\Page;

class Auth extends Page
{
    protected string | array $middlewares = [\Bengr\Auth\Http\Middleware\Authenticate::class . ':admin'];
}
