<?php

namespace Bengr\Admin\Tests\Support\TestResources\GlobalActions\CustomName;

use Bengr\Admin\GlobalActions\GlobalAction;

class CustomName extends GlobalAction
{
    protected ?string $name = 'custom-global-action-name';
}
