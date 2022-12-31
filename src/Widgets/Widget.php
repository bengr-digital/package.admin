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
    protected ?int $sort = null;

    protected ?int $id = null;

    protected ?string $name = null;

    protected int $columnSpan = 12;

    public function columnSpan(int $columnSpan): self
    {
        $this->columnSpan = $columnSpan;

        return $this;
    }

    public function column(int $columnSpan): self
    {
        $this->columnSpan = $columnSpan;

        return $this;
    }

    public function id(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSort(): int
    {
        return $this->sort ?? -1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name ?? Str::of(class_basename(static::class))
            ->kebab()
            ->slug();
    }

    public function getColumnSpan(): int
    {
        return $this->columnSpan;
    }

    public function hasWidgets(): bool
    {
        return false;
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
