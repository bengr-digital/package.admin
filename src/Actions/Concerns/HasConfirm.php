<?php

namespace Bengr\Admin\Actions\Concerns;

trait HasConfirm
{
    protected ?string $confirmTitle = null;

    protected ?string $confirmDescription = null;

    protected bool $hasConfirm = false;

    public function confirm(string $title, string $description): static
    {
        $this->confirmTitle = $title;
        $this->confirmDescription = $description;
        $this->hasConfirm = true;

        return $this;
    }

    public function hasConfirm(): bool
    {
        return $this->hasConfirm;
    }

    public function getConfirmTitle(): ?string
    {
        return $this->confirmTitle;
    }

    public function getConfirmDescription(): ?string
    {
        return $this->confirmDescription;
    }
}
