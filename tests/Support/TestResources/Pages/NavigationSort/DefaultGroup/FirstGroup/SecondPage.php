<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\DefaultGroup\FirstGroup;

use Bengr\Admin\Pages\Page;

class SecondPage extends Page
{
    protected ?string $title = 'First Group Second Page';

    protected ?string $navigationGroup = 'first-group';
}
