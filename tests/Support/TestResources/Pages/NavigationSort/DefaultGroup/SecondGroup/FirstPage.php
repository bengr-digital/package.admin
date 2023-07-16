<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\DefaultGroup\SecondGroup;

use Bengr\Admin\Pages\Page;

class FirstPage extends Page
{
    protected ?string $title = 'Second Group First Page';

    protected ?string $navigationGroup = 'second-group';
}
