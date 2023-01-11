<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Concerns\EvaluatesClosures;
use Bengr\Admin\Http\Resources\WidgetResource;
use Illuminate\Http\Request;
use Bengr\Admin\Actions\Concerns;
use Bengr\Admin\Http\Resources\ActionResource;
use Illuminate\Support\Str;

class ActionWidget extends Widget
{
    use EvaluatesClosures;
    use Concerns\HasName;
    use Concerns\HasLabel;
    use Concerns\HasIcon;
    use Concerns\HasColor;
    use Concerns\HasSize;
    use Concerns\HasRoute;
    use Concerns\HasTooltip;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanHandleModal;
    use Concerns\CanHandleAction;

    protected ?string $widgetName = 'action';

    protected int $widgetColumnSpan = 12;

    protected array $widgets = [];

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(?string $name = null): static
    {
        return app(static::class, [
            'name' => $name ?? Str::of(class_basename(static::class))->kebab()->slug()
        ]);
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function getData(Request $request): array
    {
        return [
            'label' => $this->getLabel(),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'size' => $this->getSize(),
            'tooltip' => $this->getTooltip(),
            'isDisabled' => $this->isDisabled(),
            'isHidden' => $this->isHidden(),
            'redirect' => $this->getRouteName() && $this->getRouteUrl() ? [
                'name' => $this->getRouteName(),
                'url' => $this->getRouteUrl(),
            ] : null,
            'modal' => $this->getModalId() && $this->getModalEvent() ? [
                'id' => $this->getModalId(),
                'event' => $this->getModalEvent()
            ] : null,
            'call' => $this->hasHandle() ? [
                'name' => $this->getName(),
                'widget_id' => $this->getHandleWidgetId()
            ] : null
        ];
    }
}
