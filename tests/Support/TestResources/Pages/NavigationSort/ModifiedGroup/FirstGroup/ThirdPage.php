<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\ModifiedGroup\FirstGroup;

use Bengr\Admin\Pages\Page;

class ThirdPage extends Page
{
    protected ?string $title = 'First Group Third Page';

    protected ?int $navigationSort = 0;

    protected ?string $navigationGroup = 'first-group';
}
