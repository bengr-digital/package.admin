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

use function Bengr\Support\response;

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
        $form = $this->getForm(collect([]));
        $payload = collect($payload)->map(function ($value, $key) use ($form) {
            $input = $form->getInput($key);

            if (!$input) return null;

            $input->value($value);

            return $input->transformValue();
        })->toArray();

        $action = collect($this->getActions())->where(function (ActionWidget $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action && !$this->submit_method && $name !== 'submit') return response()->throw(ActionNotFoundException::class);

        $validated = $form->validate($payload);

        if ($this->submit_method && $name === 'submit') return $this->getSubmitMethod()($this, $validated);

        return $action->getHandleMethod()($this, $validated);
    }

    public function getWidgets(): array
    {
        return $this->schema ?? [];
    }

    public function getData(Request $request): array
    {
        $form = $this->getForm(collect([]));
        $this->fill($this->record);

        return [
            'children' => WidgetResource::collection($form->getSchema())
        ];
    }
}
