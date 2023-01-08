<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Concerns\InteractsWithForm;
use Bengr\Admin\Http\Resources\WidgetResource;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;

class FormWidget extends Widget implements HasForm
{
    use InteractsWithForm;

    protected ?string $widgetName = 'form';

    protected int $widgetColumnSpan = 12;

    protected string $model;

    protected array $schema = [];

    final public function __construct($model)
    {
        $this->model = $model;
    }

    public static function make(string $model): static
    {
        return app(static::class, ['model' => $model]);
    }

    public function schema(array $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    protected function getFormSchema(): array
    {
        return $this->schema ?? [];
    }

    public function getData(Request $request): array
    {
        $form = $this->getForm(collect([]));

        return [
            'children' => WidgetResource::collection($form->getSchema())
        ];
    }
}
