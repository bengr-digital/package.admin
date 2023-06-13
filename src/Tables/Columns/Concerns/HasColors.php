<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasColors
{
    protected string | \Closure | null $color = null;

    public function color(string | \Closure | null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function colors(array | \Closure $colors): static
    {
        $colors = $this->evaluate($colors);

        $this->color(function ($value) use ($colors) {
            $stateColor = null;

            foreach ($colors as $color => $condition) {
                if ($condition == $value) {
                    $stateColor = $color;
                }
            }

            return $stateColor;
        });

        return $this;
    }

    public function getColor(Model $record): ?string
    {
        return $this->evaluate($this->color, ['value' => $this->getValue($record)]);
    }
}
