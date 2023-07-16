<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GlobalSearch\DisabledOrEnabled;

use Bengr\Admin\GlobalSearch\GlobalSearchResult;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Illuminate\Database\Eloquent\Model;

class EnabledWithAll extends Page
{
    protected ?string $slug = 'enabled-with-all';

    protected ?string $globalSearchModel = Subpage::class;

    public function getGlobalSearchAttributes(): array
    {
        return ['title'];
    }

    public function getGlobalSearchResult(Model $record): ?GlobalSearchResult
    {
        return GlobalSearchResult::make($record->title);
    }
}
