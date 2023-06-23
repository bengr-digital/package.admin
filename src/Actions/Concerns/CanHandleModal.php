<?php

namespace Bengr\Admin\Actions\Concerns;

trait CanHandleModal
{
    protected ?int $modalId = null;

    protected ?string $modalCodeId = null;

    protected ?string $modalEvent = null;

    public function modal(int | string | null $id = null, ?string $event = 'open'): static
    {
        if (is_int($id)) {
            $this->modalId = $id;
        } else {
            $this->modalCodeId = $id ?? $this->getName();
        }

        $this->modalEvent = $event;

        return $this;
    }

    public function getModalId(): ?int
    {
        return $this->modalId;
    }

    public function getModalCodeId(): ?string
    {
        return $this->modalCodeId;
    }

    public function getModalEvent(): string
    {
        return $this->modalEvent ?? 'open';
    }
}
