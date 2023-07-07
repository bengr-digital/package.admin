<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\ForNavigation\NestedWithExcluded;

use Bengr\Admin\Pages\Page;

class FirstPage extends Page
{
    protected ?string $parent = SecondPage::class;
}
