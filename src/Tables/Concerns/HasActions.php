<?php

namespace Bengr\Admin\Tables\Concerns;


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
