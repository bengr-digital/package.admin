<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithNestedParam;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Bengr\Admin\Tests\Support\TestResources\Models\SubpageContent;

class WithNestedParam extends Page
{
    protected ?string $slug = '/subpages/{subpage}/content/{content}';

    public Subpage $subpage;

    public SubpageContent $content;
}
