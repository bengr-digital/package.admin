<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\ForNavigation\WithExcluded;

use Bengr\Admin\Pages\Page;

class SecondPage extends Page
{
    protected bool $inNavigation = false;
}
