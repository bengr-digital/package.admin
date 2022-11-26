<?php

namespace Bengr\Admin\Actions\Concerns;

trait HasName
{
    protected string $name;

    public function name(string $name): static
    {
        $this->label = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
