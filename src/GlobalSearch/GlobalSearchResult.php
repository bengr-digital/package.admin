<?php

namespace Bengr\Admin\GlobalSearch;

use Bengr\Admin\Actions\Action;

class GlobalSearchResult
{
    protected ?string $title = null;

    protected ?string $description = null;

    protected ?array $redirect = null;

    protected ?string $iconName = null;

    protected ?string $iconType = null;

    protected ?string $activeIconName = null;

    protected ?string $activeIconType = null;

    protected ?string $image = null;

    final public function __construct()
    {
    }

    public static function make(): static
    {
        return new static();
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function redirect(string $page, array $params = []): self
    {
        $action = Action::make()->redirect($page, $params);

        $this->redirect = [
            'name' => $action->getRedirectName(),
            'url' => $action->getRedirectUrl(),
            'inNewTab' => $action->openInNewTab()
        ];

        return $this;
    }

    public function icon(string $iconName, string $iconType = 'outlined'): self
    {
        $this->iconName = $iconName;
        $this->iconType = $iconType;

        return $this;
    }

    public function activeIcon(string $iconName, string $iconType = 'outlined'): self
    {
        $this->activeIconName = $iconName;
        $this->activeIconType = $iconType;

        return $this;
    }

    public function image(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getRedirect(): ?array
    {
        return $this->redirect;
    }

    public function getIcon(): ?array
    {
        return [
            'name' => $this->iconName,
            'type' => $this->iconType,
            'activeName' => $this->activeIconName ?? $this->iconName,
            'activeType' => $this->activeIconType ?? $this->iconType,
        ];
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
