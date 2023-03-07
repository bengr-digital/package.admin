<?php

namespace Bengr\Admin\Tables\Concerns;

use Bengr\Admin\Actions\Action;
use Illuminate\Database\Eloquent\Model;

trait HasActionOnClick
{
    public function getCachedTableActionOnClick(): ?Action
    {
        return $this->getTableActionOnClick();
    }

    protected function getTableActionOnClick(): ?Action
    {
        return null;
    }
}
