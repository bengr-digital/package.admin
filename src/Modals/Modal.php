<?php

namespace Bengr\Admin\Modals;

use Bengr\Admin\Concerns\EvaluatesClosures;

class Modal
{
    use EvaluatesClosures;

    protected ?int $id = null;

    protected ?string $codeId = null;

    protected ?string $type = null;

    protected ?string $direction = null;

    protected ?string $heading = null;

    protected ?string $subheading = null;

    protected array | \Closure $widgets = [];

    protected array $actions = [];

    protected array $params = [];

    protected bool $hasCross = false;

    protected bool $lazyload = false;

    final public function __construct(string $codeId)
    {
        $this->codeId = $codeId;
    }

    public static function make(string $codeId): static
    {
        return app(static::class, ['codeId' => $codeId]);
    }

    public function id(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function heading(string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function subheading(string $subheading): self
    {
        $this->subheading = $subheading;

        return $this;
    }

    public function direction(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function widgets(array | \Closure $widgets): self
    {
        $this->widgets = $widgets;

        return $this;
    }

    public function cross(bool $condition = true): self
    {
        $this->hasCross = $condition;

        return $this;
    }

    public function actions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function lazyload(bool $lazyload = true): self
    {
        $this->lazyload = $lazyload;

        return $this;
    }

    public function params(array $params = []): self
    {
        $this->params = $params;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type ?? 'card';
    }

    public function getDirection(): string
    {
        return $this->direction ?? ($this->getType() == 'drawer' ? 'right' : 'center');
    }

    public function getWidgets(): array
    {
        return $this->evaluate($this->widgets, ['params' => $this->getParams()]);
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function getSubheading(): ?string
    {
        return $this->subheading;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function hasCross(): bool
    {
        return $this->hasCross;
    }

    public function getTransformedWidgets()
    {
        return $this->getWidgetsWithAutomaticIds($this->getWidgets(), $this->getId() + 1);
    }

    public function getCodeId(): ?string
    {
        return $this->codeId;
    }

    public function getLazyload(): bool
    {
        return $this->lazyload;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getFlatWidgets(?array $widgets = null): array
    {
        $flatten = [];
        $widgets = $widgets ?? $this->getTransformedWidgets();

        foreach ($widgets as $widget) {
            $flatten[] = $widget;

            if ($widget->hasWidgets()) {
                $flatten = array_merge($flatten, $this->getFlatWidgets($widget->getWidgets()));
            }
        }

        return $flatten;
    }

    public function getWidgetsWithAutomaticIds(array $widgets, int $index): array
    {
        foreach ($widgets as $widget) {
            if (!$widget->getWidgetId()) {
                $widget->widgetId($index);
            }

            $index += 1;

            if ($widget->hasWidgets()) {
                $this->getWidgetsWithAutomaticIds($widget->getWidgets(), $index);
            }

            $index = $index + count($this->getFlatWidgets($widget->getWidgets()));
        }

        return $widgets;
    }
}
