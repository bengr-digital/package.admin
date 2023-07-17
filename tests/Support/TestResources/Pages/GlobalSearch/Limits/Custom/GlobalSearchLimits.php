<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GlobalSearch\Limits\Custom;

use Bengr\Admin\GlobalSearch\GlobalSearchResult;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Illuminate\Database\Eloquent\Model;

class GlobalSearchLimits extends Page
{
    protected ?string $globalSearchModel = Subpage::class;

    protected int $globalSearchResultsLimit = 10;

    public function getGlobalSearchAttributes(): array
    {
        return ['title'];
    }

    public function getGlobalSearchResult(Model $record): ?GlobalSearchResult
    {
        return GlobalSearchResult::make($record->title);
    }
}
