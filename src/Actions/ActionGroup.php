<?php

namespace Bengr\Admin\Actions;

class ActionGroup
{
    use Concerns\HasIcon;
    use Concerns\HasColor;
    use Concerns\HasSize;
    use Concerns\HasTooltip;
    use Concerns\CanBeHidden;

    protected array $actions = [];

    final public function __construct(array $actions)
    {
        $this->actions($actions);
    }

    public static function make(array $actions): static
    {
        return app(static::class, ['actions' => $actions]);
    }

    public function actions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }
}
