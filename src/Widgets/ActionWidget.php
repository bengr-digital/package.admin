<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Concerns\EvaluatesClosures;
use Illuminate\Http\Request;
use Bengr\Admin\Actions\Concerns;
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
    use Concerns\HasRedirect;
    use Concerns\HasTooltip;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanHandleModal;
    use Concerns\CanBeDownload;
    use Concerns\CanHandleAction;
    use Concerns\HasConfirm;

    protected ?string $widgetName = 'action';

    protected ?int $widgetColumnSpan = 12;

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
            'icon' => $this->hasIcon() ? [
                'name' => $this->getIconName(),
                'activeName' => $this->getIconName(),
                'type' => $this->getIconType(),
            ] : null,
            'color' => $this->getColor(),
            'size' => $this->getSize(),
            'tooltip' => $this->getTooltip(),
            'isDisabled' => $this->isDisabled(),
            'isHidden' => $this->isHidden(),
            'confirm' => $this->hasConfirm() ? [
                'title' => $this->getConfirmTitle(),
                'description' => $this->getConfirmDescription(),
                'color' => $this->getConfirmColor(),
                'confirmText' => $this->getConfirmConfirmText(),
                'cancelText' => $this->getConfirmCancelText(),
            ] : null,
            'redirect' => $this->getRedirectUrl() ? [
                'name' => $this->getRedirectName(),
                'url' => $this->getRedirectUrl(),
                'inNewTab' => $this->openInNewTab(),
            ] : null,
            'modal' => $this->getModalId() && $this->getModalEvent() ? [
                'id' => $this->getModalId(),
                'event' => $this->getModalEvent()
            ] : null,
            'call' => $this->hasHandle() ? [
                'name' => $this->getName(),
                'widget_id' => $this->getHandleWidgetId(),
                'download' => $this->isDownload()
            ] : null
        ];
    }
}
