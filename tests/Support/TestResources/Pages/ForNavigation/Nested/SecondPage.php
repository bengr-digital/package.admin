<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\ForNavigation\Nested;

use Bengr\Admin\Pages\Page;

class SecondPage extends Page
{
    protected ?string $parent = ThirdPage::class;
}
