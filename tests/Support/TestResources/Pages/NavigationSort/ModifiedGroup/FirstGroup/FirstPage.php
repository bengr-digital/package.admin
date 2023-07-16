<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\ModifiedGroup\FirstGroup;

use Bengr\Admin\Pages\Page;

class FirstPage extends Page
{
    protected ?string $title = 'First Group First Page';

    protected ?int $navigationSort = 1;

    protected ?string $navigationGroup = 'first-group';
}
