<?php

namespace Bengr\Admin\Actions\Concerns;

trait HasType
{
    protected ?string $type = null;

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
