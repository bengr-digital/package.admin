<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\NavigationSort\DefaultGroup\FirstGroup;

use Bengr\Admin\Pages\Page;

class FirstPage extends Page
{
    protected ?string $title = 'First Group First Page';

    protected ?string $navigationGroup = 'first-group';
}
