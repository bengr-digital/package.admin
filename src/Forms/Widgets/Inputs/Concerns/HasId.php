<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasId
{
    protected ?string $id = null;

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id ?? $this->getName();
    }
}
