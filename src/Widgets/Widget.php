<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Actions\ActionGroup;
use Bengr\Admin\Concerns\EvaluatesClosures;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Widget
{
    use EvaluatesClosures;

    protected ?int $widgetId = null;

    protected ?string $widgetName = null;

    protected ?int $columnSpan = 12;

    protected ?int $widgetSort = null;

    protected bool $lazyload = false;

    public function widgetId(int $widgetId): self
    {
        $this->widgetId = $widgetId;

        return $this;
    }

    public function columnSpan(?int $columnSpan): self
    {
        $this->columnSpan = $columnSpan;

        return $this;
    }

    public function lazyload(bool $lazyload = true): self
    {
        $this->lazyload = $lazyload;

        return $this;
    }

    public function getWidgetId(): ?int
    {
        return $this->widgetId;
    }

    public function getWidgetName(): string
    {
        return $this->widgetName ?? Str::of(class_basename(static::class))
            ->kebab()
            ->slug();
    }

    public function getColumnSpan(): ?int
    {
        return $this->columnSpan;
    }

    public function getWidgetSort(): int
    {
        return $this->widgetSort ?? 0;
    }

    public function getLazyload(): bool
    {
        return $this->lazyload;
    }

    public function getWidgets(): array
    {
        return [];
    }

    public function hasWidgets(): bool
    {
        return count($this->getWidgets()) ? true : false;
    }

    public function getActions(): array
    {
        return [];
    }

    public function getFlatActions(?array $actions = null): array
    {
        $flatten = [];
        $actions = $actions ?? $this->getActions();

        foreach ($actions as $action) {
            $flatten[] = $action;

            if ($action instanceof ActionGroup) {
                $flatten = array_merge($flatten, $this->getFlatActions($action->getActions()));
            } else {
                $flatten[] = $action;
            }
        }

        return $flatten;
    }

    public function callAction(string $name, array $payload = [])
    {
        $action = collect($this->getFlatActions())->where(function (Action $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action) throw new ActionNotFoundException($name);

        return $action->getHandleMethod()($payload);
    }

    public function getData(Request $request): array
    {
        return [];
    }
}
