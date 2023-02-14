<?php

namespace Bengr\Admin\Tables\Concerns;

use Bengr\Admin\Actions\Action;
use Illuminate\Database\Eloquent\Model;

trait HasActionOnClick
{
    public function getCachedTableActionOnClick(?Model $record): ?Action
    {
        if ($record && $this->getTableActionOnClick()) {
            $this->getTableActionOnClick()->record($record);
        }

        return $this->getTableActionOnClick();
    }

    protected function getTableActionOnClick(): ?Action
    {
        return null;
    }
}
