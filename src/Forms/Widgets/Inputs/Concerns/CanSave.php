<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

use Illuminate\Database\Eloquent\Model;


trait CanSave
{
    public function save(Model | array &$record, bool $isNew = true, string $name = null): self
    {
        $name = $name ?? $this->getName();

        if ($record instanceof Model) {
            $record->fill([$name => $this->getValue()]);
        } else {
            $record[$name] = $this->getValue();
        }


        return $this;
    }
}
