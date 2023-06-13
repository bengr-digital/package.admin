<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Http\Resources\WidgetResource;
use Illuminate\Http\Request;

class BoxWidget extends Widget
{
    protected ?string $widgetName = 'box';

    protected ?int $widgetColumnSpan = 12;

    protected array $widgets = [];

    final public function __construct(array $widgets)
    {
        $this->widgets = $widgets;
    }

    public static function make(array $widgets): static
    {
        return app(static::class, ['widgets' => $widgets]);
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function getData(Request $request): array
    {
        return [
            'children' => WidgetResource::collection($this->getWidgets())
        ];
    }
}
