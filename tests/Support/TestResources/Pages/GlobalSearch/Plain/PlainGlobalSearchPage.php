<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GlobalSearch\Plain;

use Bengr\Admin\GlobalSearch\GlobalSearchResult;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Illuminate\Database\Eloquent\Model;

class PlainGlobalSearchPage extends Page
{
    protected ?string $globalSearchModel = Subpage::class;

    public function getGlobalSearchAttributes(): array
    {
        return ['title'];
    }

    public function getGlobalSearchResult(Model $record): ?GlobalSearchResult
    {
        return GlobalSearchResult::make()
            ->title($record->title);
    }
}
