<?php

namespace Bengr\Admin\Actions\Concerns;

trait HasConfirm
{
    protected ?string $confirmTitle = null;

    protected ?string $confirmDescription = null;

    protected string $confirmCancelText = 'Cancel';

    protected string $confirmConfirmText = 'Confirm';

    protected ?string $confirmColor = null;

    protected bool $hasConfirm = false;

    public function confirm(string $title, string $description): static
    {
        $this->confirmTitle = $title;
        $this->confirmDescription = $description;
        $this->hasConfirm = true;

        return $this;
    }

    public function confirmButtons(string $confirmText, string $cancelText): static
    {
        $this->confirmConfirmText = $confirmText;
        $this->confirmCancelText = $cancelText;

        return $this;
    }

    public function confirmColor(string $color): static
    {
        $this->confirmColor = $color;

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

    public function getConfirmCancelText(): ?string
    {
        return $this->confirmCancelText;
    }

    public function getConfirmConfirmText(): ?string
    {
        return $this->confirmConfirmText;
    }

    public function getConfirmColor(): ?string
    {
        return $this->confirmColor;
    }
}
