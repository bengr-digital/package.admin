<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithParam;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;

class WithParamWithColumn extends Page
{
    protected ?string $slug = '/subpages/{subpage:name_code}/with-column';

    public Subpage $subpage;
}
