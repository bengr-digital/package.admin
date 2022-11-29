<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    public function getCachedTableModel(): ?Model
    {
        return $this->getTableModel();
    }

    protected function getTableModel(): ?Model
    {
        return null;
    }
}
