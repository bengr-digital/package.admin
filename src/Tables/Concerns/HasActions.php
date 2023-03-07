<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasActions
{
    public function getCachedTableActions(): array
    {
        return $this->getTableActions();
    }

    protected function getTableActions(): array
    {
        return [];
    }
}
