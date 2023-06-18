<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

use Illuminate\Database\Eloquent\Model;

trait CanSave
{
    public function save(Model $record): self
    {
        $record->fill([$this->getName() => $this->getValue()]);

        return $this;
    }
}
