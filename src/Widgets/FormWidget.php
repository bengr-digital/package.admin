<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
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

    protected ?\Closure $submit_method = null;

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

    public function submit(?\Closure $callback): self
    {
        $this->submit_method = $callback;

        return $this;
    }

    protected function getSubmitMethod(): ?\Closure
    {
        return $this->submit_method;
    }

    public function getActions(): array
    {
        return [
            Action::make('submit')
                ->handle(function (array $payload) {
                    $form = $this->getForm(collect([]));

                    $form->validate($payload);

                    $this->getSubmitMethod()($payload);
                })
        ];
    }

    protected function getFormSchema(): array
    {
        return $this->schema ?? [];
    }

    public function getData(Request $request): array
    {
        $form = $this->getForm(collect([]));

        $this->fillDefaultState();

        return [
            'children' => WidgetResource::collection($form->getSchema())
        ];
    }
}
