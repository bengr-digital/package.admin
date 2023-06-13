<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasName
{
    protected ?string $name = null;

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
