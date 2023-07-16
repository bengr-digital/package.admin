<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomProperties\Breadcrumbs;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Pages\Simple\Simple;

class WithParent extends Page
{
    protected ?string $parent = Simple::class;
}
