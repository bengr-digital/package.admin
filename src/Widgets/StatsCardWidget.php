<?php

namespace Bengr\Admin\Widgets;

use Illuminate\Http\Request;

class StatsCardWidget extends Widget
{
    protected ?string $widgetName = 'stats-card';

    protected ?int $widgetColumnSpan = 4;

    protected string $label;

    protected string $value;

    protected ?string $icon = null;

    protected ?string $color = null;

    protected ?string $description = null;

    protected ?string $descriptionIcon = null;

    protected ?string $descriptionColor = null;

    protected array $chart = [];

    protected ?string $chartColor = null;

    protected array $extraAttributes = [];

    final public function __construct(string $label, $value)
    {
        $this->label($label);
        $this->value($value);
    }

    public static function make(string $label, $value): static
    {
        return app(static::class, ['label' => $label, 'value' => $value]);
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function value(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function icon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function color(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function description(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function descriptionColor(?string $descriptionColor): self
    {
        $this->descriptionColor = $descriptionColor;

        return $this;
    }

    public function descriptionIcon(?string $descriptionIcon): self
    {
        $this->descriptionIcon = $descriptionIcon;

        return $this;
    }

    public function chart(array $chart): self
    {
        $this->chart = $chart;

        return $this;
    }

    public function chartColor(?string $chartColor): self
    {
        $this->chartColor = $chartColor;

        return $this;
    }

    public function extraAttributes(array $extraAttributes): self
    {
        $this->extraAttributes = $extraAttributes;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDescriptionIcon(): ?string
    {
        return $this->descriptionIcon;
    }

    public function getDescriptionColor(): ?string
    {
        return $this->descriptionColor ?? $this->color;
    }

    public function getChart(): ?array
    {
        return $this->chart;
    }

    public function getChartColor(): ?string
    {
        return $this->chartColor ?? $this->color;
    }

    public function getExtraAttributes(): ?array
    {
        return $this->extraAttributes;
    }

    public function getData(Request $request): array
    {
        return [
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'description' => $this->getDescription(),
            'descriptionIcon' => $this->getDescriptionIcon(),
            'descriptionColor' => $this->getDescriptionColor(),
            'chart' => $this->getChart(),
            'chartColor' => $this->getChartColor(),
            'extraAttributes' => $this->getExtraAttributes()
        ];
    }
}
