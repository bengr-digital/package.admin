<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Concerns\InteractsWithForm;
use Bengr\Admin\Forms\Form;
use Bengr\Admin\Http\Resources\WidgetResource;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use function Bengr\Support\response;

class FormWidget extends Widget implements HasForm
{
    use InteractsWithForm;

    protected ?string $widgetName = 'form';

    protected ?int $widgetColumnSpan = 12;

    protected Form $form;

    protected Page $page;

    protected array $schema = [];

    protected ?\Closure $submit_method = null;


    final public function __construct($model, $page)
    {
        $this->page = $page;
        $this->model($model);
        $this->form = $this->getForm(collect());
    }

    public static function make(string $model, Page $page = null): static
    {
        return app(static::class, ['model' => $model, 'page' => $page]);
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

    public function callAction(string $name, array $payload = [])
    {
        $this->record($this->page);
        $this->fill($payload);

        $action = collect($this->getActions())->where(function (ActionWidget $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action && !$this->submit_method && $name !== 'submit') return response()->throw(ActionNotFoundException::class);

        $this->form->validate();

        if ($this->submit_method && $name === 'submit') return $this->getSubmitMethod()($this->form);

        return $action->getHandleMethod()($this->form);
    }

    public function getWidgets(): array
    {
        return $this->schema ?? [];
    }

    public function getData(Request $request): array
    {
        $this->record($this->page);
        $this->fill($this->getRecord());

        return [
            'children' => WidgetResource::collection($this->form->getSchema())
        ];
    }
}
