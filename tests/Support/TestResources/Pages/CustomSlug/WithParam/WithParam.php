<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithParam;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;

class WithParam extends Page
{
    protected ?string $slug = '/subpages/{subpage}';

    public Subpage $subpage;
}
