<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomProperties\Breadcrumbs;

use Bengr\Admin\Pages\Page;

class WithParentWithParent extends Page
{
    protected ?string $parent = withParent::class;
}
