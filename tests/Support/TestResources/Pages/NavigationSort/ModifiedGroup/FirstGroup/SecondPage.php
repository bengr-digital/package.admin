<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\ModifiedGroup\FirstGroup;

use Bengr\Admin\Pages\Page;

class SecondPage extends Page
{
    protected ?string $title = 'First Group Second Page';

    protected ?int $navigationSort = 2;

    protected ?string $navigationGroup = 'first-group';
}
