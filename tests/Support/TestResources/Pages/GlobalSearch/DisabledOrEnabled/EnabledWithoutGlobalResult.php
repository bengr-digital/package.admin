<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GlobalSearch\DisabledOrEnabled;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;

class EnabledWithoutGlobalResult extends Page
{
    protected ?string $slug = 'enabled-without-global-result';

    protected ?string $globalSearchModel = Subpage::class;

    public function getGlobalSearchAttributes(): array
    {
        return ['title'];
    }
}
