<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasLabel
{
    protected ?string $label = null;

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
}
