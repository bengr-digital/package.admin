<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\GlobalSearch\DisabledOrEnabled;

use Bengr\Admin\GlobalSearch\GlobalSearchResult;
use Bengr\Admin\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class DisabledWithoutGlobalModel extends Page
{
    protected ?string $slug = 'disabled-without-global-model';

    public function getGlobalSearchAttributes(): array
    {
        return ['title'];
    }

    public function getGlobalSearchResult(Model $record): ?GlobalSearchResult
    {
        return GlobalSearchResult::make($record->title);
    }
}
