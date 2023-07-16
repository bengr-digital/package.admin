<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Concerns\InteractsWithForm;
use Bengr\Admin\Forms\Form;
use Bengr\Admin\Http\Resources\ActionResource;
use Bengr\Admin\Http\Resources\WidgetResource;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;

class FormWidget extends Widget implements HasForm
{
    use InteractsWithForm;

    protected ?string $widgetName = 'form';

    protected ?int $columnSpan = 12;

    protected Form $form;

    protected Page $page;

    protected array $schema = [];

    protected ?bool $detectUnsavedChanges = null;

    protected ?\Closure $submit_method = null;

    final public function __construct($model)
    {
        $this->model($model);
        $this->form = $this->getForm(collect());
    }

    public static function make(string $model, Page $page = null): static
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

    public function detectUnsavedChanges(bool | \Closure $condition = true): self
    {
        $this->detectUnsavedChanges = $condition;

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
        return $this->getAutomatedFormSchema();
    }

    protected function getDetectUnsavedChanges(): bool
    {
        if (is_null($this->detectUnsavedChanges)) {
            return !!$this->getRecord();
        }

        return $this->evaluate($this->detectUnsavedChanges);
    }

    public function callAction(string $name, array $payload = [])
    {
        $this->fill($payload);


        $action = collect($this->getActions())->where(function (ActionWidget $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action && !$this->submit_method && $name !== 'submit') throw new ActionNotFoundException();
        $this->form->validate();

        if ($this->submit_method && $name === 'submit') return $this->getSubmitMethod()($this->form);

        return $action->getHandleMethod()($this->form);
    }

    public function getWidgets(): array
    {
        return $this->getAutomatedFormSchema();
    }

    protected function getAutomatedFormSchema(?array $widgets = null): array
    {
        $widgets = $widgets ?? $this->schema;

        foreach ($widgets as $widget) {
            if ($widget instanceof FormWidget) break;

            if ($widget instanceof ActionWidget && !$widget->hasHandle() && $widget->getName() == 'submit') {
                $widget->handle(null, null);
                $widget->type('submit');
            }

            if ($widget instanceof ActionWidget && !$widget->getRecord() && $this->getRecord()) {
                $widget->record($this->getRecord());
            }

            if ($widget->hasWidgets()) {
                $this->getAutomatedFormSchema($widget->getWidgets());
            }
        }

        return $widgets;
    }

    public function getData(Request $request): array
    {
        $this->fill($this->getRecord());

        return [
            'actionOnSubmit' => ActionResource::make(Action::make('submit')->handle(null, $this->getWidgetId())),
            'detectUnsavedChanges' => $this->getDetectUnsavedChanges(),
            'children' => WidgetResource::collection($this->form->getSchema())
        ];
    }
}
