<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GroupedWithNested;

use Bengr\Admin\Pages\Page;

class ThirdPage extends Page
{
    protected ?string $parent = SecondPage::class;
}
