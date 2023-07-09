<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithUnknownParam;

use Bengr\Admin\Pages\Page;

class WithUnknownParam extends Page
{
    protected ?string $slug = '/subpages/{subpage}';
}
