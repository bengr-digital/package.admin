<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\DefaultGroup\FirstGroup;

use Bengr\Admin\Pages\Page;

class ThirdPage extends Page
{
    protected ?string $title = 'First Group Third Page';

    protected ?string $navigationGroup = 'first-group';
}
