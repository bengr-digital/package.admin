<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GroupedWithNested;

use Bengr\Admin\Pages\Page;

class SixthPage extends Page
{
    protected ?string $parent = FifthPage::class;
}
