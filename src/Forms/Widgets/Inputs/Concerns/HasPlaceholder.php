<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasPlaceholder
{
    protected ?string $placeholder = null;

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }
}
