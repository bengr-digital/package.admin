<?php

namespace Bengr\Admin\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;

trait InteractsWithRecord
{
    protected ?Model $record = null;

    public function record(?Model $record = null): static
    {
        $this->record = $record;

        return $this;
    }

    public function getRecord(): ?Model
    {
        return $this->record;
    }
}
