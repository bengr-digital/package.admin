<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Concerns\InteractsWithForm;
use Bengr\Admin\Http\Resources\WidgetResource;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FormWidget extends Widget implements HasForm
{
    use InteractsWithForm;

    protected ?string $widgetName = 'form';

    protected int $widgetColumnSpan = 12;

    protected string $model;

    protected ?Model $record = null;

    protected array $schema = [];

    protected ?\Closure $submit_method = null;

    final public function __construct($model, $record)
    {
        $this->record = $record;
        $this->model = $model;
        $this->fillDefaultState();
    }

    public static function make(string $model, ?Model $record = null): static
    {
        return app(static::class, ['model' => $model, 'record' => $record]);
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
        return $this->getFormActions();
    }

    protected function getFormSchema(): array
    {
        return $this->schema ?? [];
    }

    public function getRecord(): ?Model
    {
        return $this->record;
    }

    public function callAction(string $name, array $payload = [])
    {
        $action = collect($this->getActions())->where(function (ActionWidget $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action) return response()->throw(ActionNotFoundException::class);

        return $action->getHandleMethod()($this, $payload);
    }

    public function getWidgets(): array
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
