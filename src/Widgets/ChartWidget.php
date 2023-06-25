<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;

class ChartWidget extends Widget
{
    protected ?string $widgetName = 'chart';

    protected ?int $widgetColumnSpan = 6;

    protected string $heading;

    protected ?string $subheading = null;

    protected ?string $type = null;

    protected array | \Closure | null $labels = [];

    protected array $datasets = [];

    final public function __construct(string $heading, string $subheading = null)
    {
        $this->heading($heading);
        $this->subheading($subheading);
    }

    public static function make(string $heading, string $subheading = null): static
    {
        return app(static::class, ['heading' => $heading, 'subheading' => $subheading]);
    }

    public function heading(string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function subheading(?string $subheading = null): self
    {
        $this->subheading = $subheading;

        return $this;
    }

    public function labels(array | \Closure | null $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    public function dataset(string $label, array | \Closure | null $data): self
    {
        $this->datasets[] = [
            'label' => $label,
            'data' => $data,
        ];

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

    public function getLabels(): array
    {
        return $this->evaluate($this->labels);
    }

    public function getDatasets(): array
    {
        return collect($this->datasets)->map(fn ($dataset) => ['label' => $dataset['label'], 'data' => $this->evaluate($dataset['data'])])->toArray();
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getData(Request $request): array
    {
        return [
            'heading' => $this->getHeading(),
            'subheading' => $this->getSubheading(),
            'type' => $this->getType(),
            'datasets' => $this->getDatasets(),
            'labels' => $this->getLabels()
        ];
    }
}
