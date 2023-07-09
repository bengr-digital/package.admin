<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithParam;

use Bengr\Admin\Pages\Page;

class WithParamWithoutProperty extends Page
{
    protected ?string $slug = '/subpages/{subpage}/without-property';
}
