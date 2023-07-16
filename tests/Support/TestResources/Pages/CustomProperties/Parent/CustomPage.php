<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomProperties\Parent;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Pages\Simple\Simple;

class CustomPage extends Page
{
    protected ?string $parent = Simple::class;
}
