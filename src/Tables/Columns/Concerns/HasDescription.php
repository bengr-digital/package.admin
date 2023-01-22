<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasDescription
{
    protected string | \Closure | null $descriptionAbove = null;

    protected string | \Closure | null $descriptionBelow = null;

    public function description(string | \Closure | null $description, string | \Closure | null $position = 'below'): static
    {
        if ($position == 'above') {
            $this->descriptionAbove = $description;
        } else {
            $this->descriptionBelow = $description;
        }

        return $this;
    }

    /**
     * @deprecated Use `description(position: 'above')` instead.
     */
    public function descriptionPosition(string $position = 'below'): static
    {
        if ($position === 'above') {
            $this->descriptionAbove = $this->descriptionBelow;
            $this->descriptionBelow = null;
        }

        return $this;
    }

    public function getDescriptionAbove(Model $record): string | null
    {
        return $this->evaluate($this->descriptionAbove, ['record' => $record]);
    }

    public function getDescriptionBelow(Model $record): string | null
    {
        return $this->evaluate($this->descriptionBelow, ['record' => $record]);
    }
}
