<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Http\Resources\WidgetResource;
use Illuminate\Http\Request;

class CardWidget extends Widget
{
    protected ?string $widgetName = 'card';

    protected ?int $widgetColumnSpan = 12;

    protected array $widgets = [];

    protected ?string $heading = null;

    protected ?string $subheading = null;

    protected array $footer = [];

    final public function __construct(array $widgets)
    {
        $this->widgets($widgets);
    }

    public static function make(array $widgets): static
    {
        return app(static::class, ['widgets' => $widgets]);
    }

    public function widgets(array $widgets): self
    {
        $this->widgets = $widgets;

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

    public function footer(array $widgets): self
    {
        $this->footer = $widgets;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function getSubheading(): ?string
    {
        return $this->subheading;
    }

    public function getFooter(): array
    {
        return $this->footer ?? [];
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function hasWidgets(): bool
    {
        return true;
    }

    public function getData(Request $request): ?array
    {
        return [
            'header' => [
                'heading' => $this->getHeading(),
                'subheading' => $this->getSubheading(),
            ],
            'footer' => WidgetResource::collection($this->getFooter()),
            'children' => WidgetResource::collection($this->getWidgets())
        ];
    }
}
