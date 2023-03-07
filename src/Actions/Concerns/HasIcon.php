<?php

namespace Bengr\Admin\Actions\Concerns;

use Closure;

trait HasIcon
{
    protected string | Closure | null $icon_name = null;
    protected string | Closure | null $icon_type = null;

    public function icon(string | Closure | null $icon_name, string | Closure | null $icon_type): static
    {
        $this->icon_name = $icon_name;
        $this->icon_type = $icon_type;

        return $this;
    }

    public function getIconName(): ?string
    {
        return $this->evaluate($this->icon_name);
    }

    public function getIconType(): ?string
    {
        return $this->evaluate($this->icon_type);
    }

    public function hasIcon(): bool
    {
        return $this->icon_name && $this->icon_type;
    }
}
