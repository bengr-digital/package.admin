<?php

namespace Bengr\Admin\Actions\Concerns;

use Closure;

trait HasIcon
{
    protected string | Closure | null $iconName = null;

    protected string | Closure | null $iconType = null;

    protected string | Closure | null $activeIconName = null;

    protected string | Closure | null $activeIconType = null;

    public function icon(string | Closure | null $iconName, string | Closure | null $iconType = 'outlined'): static
    {
        $this->iconName = $iconName;
        $this->iconType = $iconType;

        return $this;
    }

    public function activeIcon(string | Closure | null $activeIconName, string | Closure | null $activeIconType): static
    {
        $this->activeIconName = $activeIconName;
        $this->activeIconType = $activeIconType;

        return $this;
    }

    public function getIconName(): ?string
    {
        return $this->evaluate($this->iconName);
    }

    public function getIconType(): ?string
    {
        return $this->evaluate($this->iconType);
    }

    public function getActiveIconName(): ?string
    {
        return $this->evaluate($this->activeIconName) ?? $this->getIconName();
    }

    public function getActiveIconType(): ?string
    {
        return $this->evaluate($this->activeIconType) ?? $this->getIconType();
    }

    public function hasIcon(): bool
    {
        return $this->iconName && $this->iconType;
    }
}
