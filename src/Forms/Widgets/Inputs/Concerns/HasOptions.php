<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasOptions
{
    protected array $options = [];

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return collect($this->options)->map(function ($value, $key) {
            return [
                'value' => $key,
                'label' => $value,
                'disabled' => false,
                'readonly' => false,
                'hidden' => false
            ];
        })->values()->toArray();
    }
}
