<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Actions\ActionGroup;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use function Bengr\Support\response;

class Widget
{
    protected ?int $widgetSort = null;

    protected ?int $widgetId = null;

    protected ?string $widgetName = null;

    protected ?int $widgetColumnSpan = 12;

    protected bool $showOnlyChildren = false;

    protected bool $withoutProps = false;

    protected bool $lazyload = false;

    protected $transformed_actions;

    public function widgetColumnSpan(?int $widgetColumnSpan): self
    {
        $this->widgetColumnSpan = $widgetColumnSpan;

        return $this;
    }

    public function widgetColumn(?int $widgetColumnSpan): self
    {
        $this->widgetColumnSpan = $widgetColumnSpan;

        return $this;
    }

    public function showOnlyChildren(bool $condition = true): self
    {
        $this->showOnlyChildren = $condition;

        return $this;
    }

    public function withoutProps(bool $condition = true): self
    {
        $this->withoutProps = $condition;

        return $this;
    }

    public function widgetId(int $widgetId): self
    {
        $this->widgetId = $widgetId;

        return $this;
    }

    public function lazyload(bool $lazyload = true): self
    {
        $this->lazyload = $lazyload;

        return $this;
    }

    public function getWidgetSort(): int
    {
        return $this->widgetSort ?? -1;
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

    public function getWidgetColumnSpan(): ?int
    {
        return $this->widgetColumnSpan;
    }

    public function getShowOnlyChildren(): bool
    {
        return $this->showOnlyChildren;
    }

    public function getWithoutProps(): bool
    {
        return $this->withoutProps;
    }

    public function hasWidgets(): bool
    {
        return count($this->getWidgets()) ? true : false;
    }

    public function getLazyload(): bool
    {
        return $this->lazyload;
    }


    public function getWidgets(): array
    {
        return [];
    }

    public function getActions(): array
    {
        return [];
    }

    protected function loopActions(array $actions)
    {
        collect($actions)->map(function ($action) {
            if ($action instanceof ActionGroup) {
                $this->loopActions($action->getActions());
            } else {
                $this->transformed_actions->push($action);
            }
        });
    }

    public function callAction(string $name, array $payload = [])
    {
        $this->transformed_actions = collect([]);

        $this->loopActions($this->getActions());

        $action = $this->transformed_actions->where(function (Action $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action) return response()->throw(ActionNotFoundException::class);

        return $action->getHandleMethod()($payload);
    }

    public function getData(Request $request): ?array
    {
        return null;
    }
}
