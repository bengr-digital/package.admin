<?php

namespace Bengr\Admin\Actions\Concerns;

trait CanHandleModal
{
    protected ?int $modalId = null;

    protected ?string $modalEvent = null;

    public function modal(int $id, ?string $event = null): static
    {
        $this->modalId = $id;
        $this->modalEvent = $event;

        return $this;
    }

    public function getModalId(): ?int
    {
        return $this->modalId;
    }

    public function getModalEvent(): string
    {
        return $this->modalEvent ?? 'open';
    }
}
