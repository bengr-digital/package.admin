<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Http\Resources\ActionResource;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;

class ActionCardWidget extends Widget
{
    protected ?string $widgetName = 'action-card';

    protected ?int $columnSpan = 4;

    protected string $heading;

    protected ?string $subheading = null;

    protected ?string $iconName = null;

    protected ?string $iconType = null;

    protected ?string $iconColor = null;

    protected ?string $image = null;

    protected ?Action $actionOnClick = null;

    protected bool $isCircular = false;

    protected array $actions = [];

    final public function __construct(string $heading, string $subheading)
    {
        $this->heading($heading);
        $this->subheading($subheading);
    }

    public static function make(string $heading, string $subheading): static
    {
        return app(static::class, ['heading' => $heading, 'subheading' => $subheading]);
    }

    public function heading(string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function isCircular(bool $isCircular): self
    {
        $this->isCircular = $isCircular;

        return $this;
    }

    public function subheading(string $subheading): self
    {
        $this->subheading = $subheading;

        return $this;
    }

    public function icon(string $iconName, string $iconType = 'outlined'): self
    {
        $this->iconName = $iconName;
        $this->iconType = $iconType;

        return $this;
    }

    public function iconColor(string $iconColor): self
    {
        $this->iconColor = $iconColor;

        return $this;
    }

    public function image(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function actionOnClick(?Action $actionOnClick): self
    {
        $this->actionOnClick = $actionOnClick;

        return $this;
    }

    public function actions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    protected function getHeading(): string
    {
        return $this->heading;
    }

    protected function getSubheading(): ?string
    {
        return $this->subheading;
    }

    protected function getIconName(): ?string
    {
        return $this->iconName;
    }

    protected function getIconType(): ?string
    {
        return $this->iconType;
    }

    protected function getIconColor(): ?string
    {
        return $this->iconColor;
    }

    protected function getImage(): ?string
    {
        return $this->image;
    }

    protected function getActionOnClick(): ?Action
    {
        return $this->actionOnClick;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getIsCircular(): bool
    {
        return $this->isCircular;
    }

    public function getData(Request $request): array
    {
        return [
            'heading' => $this->getHeading(),
            'subheading' => $this->getSubheading(),
            'icon' => $this->getIconName() ? [
                'name' => $this->getIconName(),
                'activeName' => $this->getIconName(),
                'type' => $this->getIconType(),
            ] : null,
            'image' => $this->getImage(),
            'actionOnClick' => $this->getActionOnClick() ? ActionResource::make($this->getActionOnClick()) : null,
            'actions' => ActionResource::collection($this->getActions()),
        ];
    }
}
