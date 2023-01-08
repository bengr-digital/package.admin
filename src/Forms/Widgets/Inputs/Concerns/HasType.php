<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasType
{
    protected ?string $type = null;

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
