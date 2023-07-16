<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\DefaultGroup\SecondGroup;

use Bengr\Admin\Pages\Page;

class SecondPage extends Page
{
    protected ?string $title = 'Second Group Second Page';

    protected ?string $navigationGroup = 'second-group';
}
