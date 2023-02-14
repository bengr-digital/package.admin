<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasActions
{
    public function getCachedTableActions(?Model $record): array
    {
        if ($record) {
            collect($this->getTableActions())->each(function ($action) use ($record) {
                $action->record($record);
            });
        }

        return $this->getTableActions();
    }

    protected function getTableActions(): array
    {
        return [];
    }
}
