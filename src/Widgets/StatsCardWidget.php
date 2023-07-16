<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;

class StatsCardWidget extends Widget
{
    protected ?string $widgetName = 'stats-card';

    protected ?int $columnSpan = 4;

    protected string $heading;

    protected ?string $subheading = null;

    protected int | string $value;

    protected ?string $description = null;

    protected ?string $descriptionColor = null;

    protected ?string $descriptionIconName = null;

    protected ?string $descriptionIconType = null;

    protected ?string $iconName = null;

    protected ?string $iconType = null;

    protected ?string $iconColor = null;

    protected array $chart = [];

    protected ?string $chartColor = null;

    final public function __construct(string $heading, int | string $value)
    {
        $this->heading($heading);
        $this->value($value);
    }

    public static function make(string $heading, $value): static
    {
        return app(static::class, ['heading' => $heading, 'value' => $value]);
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

    public function value(int | string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function descriptionColor(string $descriptionColor = null): self
    {
        $this->descriptionColor = $descriptionColor;

        return $this;
    }

    public function descriptionIcon(string $descriptionIconName, string $descriptionIconType = 'outlined'): self
    {
        $this->descriptionIconName = $descriptionIconName;
        $this->descriptionIconType = $descriptionIconType;

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

    public function chart(array $chart): self
    {
        $this->chart = $chart;

        return $this;
    }

    public function chartColor(string $chartColor): self
    {
        $this->chartColor = $chartColor;

        return $this;
    }

    public function getHeading(): string
    {
        return $this->heading;
    }

    public function getSubheading(): ?string
    {
        return $this->subheading;
    }

    public function getValue(): int | string
    {
        return $this->value;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDescriptionColor(): ?string
    {
        return $this->descriptionColor;
    }

    public function getDescriptionIconName(): ?string
    {
        return $this->descriptionIconName;
    }

    public function getDescriptionIconType(): ?string
    {
        return $this->descriptionIconType;
    }

    public function getIconName(): ?string
    {
        return $this->iconName;
    }

    public function getIconType(): ?string
    {
        return $this->iconType;
    }

    public function getIconColor(): ?string
    {
        return $this->iconColor;
    }

    public function getChart(): array
    {
        return $this->chart;
    }

    public function getChartColor(): ?string
    {
        return $this->chartColor;
    }

    public function getData(Request $request): array
    {
        return [
            'heading' => $this->getHeading(),
            'subheading' => $this->getSubheading(),
            'value' => $this->getValue(),
            'icon' => $this->getIconName() ? [
                'name' => $this->getIconName(),
                'activeName' => $this->getIconName(),
                'type' => $this->getIconType(),
            ] : null,
            'description' => $this->getDescription(),
            'descriptionIcon' => $this->getDescriptionIconName() ? [
                'name' => $this->getDescriptionIconName(),
                'activeName' => $this->getDescriptionIconName(),
                'type' => $this->getDescriptionIconType(),
            ] : null,
            'descriptionColor' => $this->getDescriptionColor(),
            'chart' => $this->getChart(),
            'chartColor' => $this->getChartColor(),
        ];
    }
}
