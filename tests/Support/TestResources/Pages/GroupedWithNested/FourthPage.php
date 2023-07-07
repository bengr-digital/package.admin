<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GroupedWithNested;

use Bengr\Admin\Pages\Page;

class FourthPage extends Page
{
    protected ?string $navigationGroup = 'testing_first';
}
