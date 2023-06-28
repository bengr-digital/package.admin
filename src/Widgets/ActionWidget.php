<?php

namespace Bengr\Admin\Widgets;

use Illuminate\Http\Request;
use Bengr\Admin\Actions\Concerns;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Illuminate\Support\Str;

class ActionWidget extends Widget
{
    use Concerns\HasName;
    use Concerns\HasLabel;
    use Concerns\HasIcon;
    use Concerns\HasColor;
    use Concerns\HasSize;
    use Concerns\HasRoute;
    use Concerns\HasRedirect;
    use Concerns\HasTooltip;
    use Concerns\HasParams;
    use Concerns\HasType;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanHandleModal;
    use Concerns\CanBeDownload;
    use Concerns\CanHandleAction;
    use Concerns\HasConfirm;
    use Concerns\InteractsWithRecord;

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

    public function transformAction(): void
    {
        $page = BengrADmin::getCurrentPage();

        if ($this->getModalCodeId() && !$this->getModalId()) {
            $modal = collect($page->getTransformedModals())->first(fn ($modal) => $modal->getCodeId() == $this->getModalCodeId());

            if ($modal) {
                $this->modal($modal->getId(), $this->getModalEvent());
            }
        }
    }

    public function getData(Request $request): array
    {
        $this->transformAction();

        return [
            'type' => $this->getType(),
            'label' => $this->getLabel(),
            'icon' => $this->hasIcon() ? [
                'name' => $this->getIconName(),
                'type' => $this->getIconType(),
                'activeName' => $this->getActiveIconName(),
                'activeType' => $this->getActiveIconType(),
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
                'event' => $this->getModalEvent(),
                'params' => $this->getParams()
            ] : null,
            'call' => $this->hasHandle() ? [
                'name' => $this->getName(),
                'widget_id' => $this->getHandleWidgetId(),
                'download' => $this->isDownload(),
                'params' => $this->getParams()
            ] : null
        ];
    }
}
