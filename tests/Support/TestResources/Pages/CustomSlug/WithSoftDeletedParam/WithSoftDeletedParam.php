<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithSoftDeletedParam;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;

class WithSoftDeletedParam extends Page
{
    protected ?string $slug = '/subpages/{subpage}';

    public Subpage $subpage;
}
