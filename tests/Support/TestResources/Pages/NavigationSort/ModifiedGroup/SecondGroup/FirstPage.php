<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\ModifiedGroup\SecondGroup;

use Bengr\Admin\Pages\Page;

class FirstPage extends Page
{
    protected ?string $title = 'Second Group First Page';

    protected ?int $navigationSort = 1;

    protected ?string $navigationGroup = 'second-group';
}
