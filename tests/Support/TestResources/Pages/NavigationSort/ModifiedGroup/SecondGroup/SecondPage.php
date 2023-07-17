<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\ModifiedGroup\SecondGroup;

use Bengr\Admin\Pages\Page;

class SecondPage extends Page
{
    protected ?string $title = 'Second Group Second Page';

    protected ?int $navigationSort = 0;

    protected ?string $navigationGroup = 'second-group';
}
