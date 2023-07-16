<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Actions\ActionGroup;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Http\Resources\ActionGroupResource;
use Bengr\Admin\Http\Resources\ColumnResource;
use Bengr\Admin\Http\Resources\WidgetResource;
use Bengr\Admin\Tables\Concerns\InteractsWithTable;
use Bengr\Admin\Tables\Contracts\HasTable;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableWidget extends Widget implements HasTable
{
    use InteractsWithTable;

    protected ?string $widgetName = 'table';

    protected ?int $columnSpan = 12;

    protected string $model;

    protected array $columns = [];

    protected array $actions = [];

    protected ?\Closure $query = null;

    protected ?Action $actionOnClick = null;

    protected array $bulkActions = [];

    protected array $filters = [];

    protected array $params = [];

    protected $transformed_actions;

    protected $transformed_bulkActions;

    final public function __construct($model)
    {
        $this->model = $model;
    }

    public static function make(string $model): static
    {
        return app(static::class, ['model' => $model]);
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function actions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function query(\Closure $query)
    {
        $this->query = $query;

        return $this;
    }

    public function actionOnClick(Action $actionOnClick): self
    {
        $this->actionOnClick = $actionOnClick;

        return $this;
    }

    public function bulkActions(array $bulkActions): self
    {
        $this->bulkActions = $bulkActions;

        return $this;
    }


    public function filters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    protected function getTableColumns()
    {
        return $this->columns ?? [];
    }

    protected function getTableQueryQuery(): ?\Closure
    {
        return $this->query;
    }

    protected function getTableActions(): array
    {
        return $this->actions ?? [];
    }

    protected function getTableActionOnClick(): ?Action
    {
        return $this->actionOnClick ?? null;
    }

    protected function getTableFilters(): array
    {
        return $this->filters ?? [];
    }

    protected function getTableBulkActions(): array
    {
        return $this->bulkActions ?? [];
    }

    protected function getTableModel()
    {
        return $this->model;
    }

    protected function loopActions(array $actions)
    {
        collect($actions)->map(function ($action) {
            if ($action instanceof ActionGroup) {
                $this->loopActions($action->getActions());
            } else {
                $this->transformed_actions->push($action);
            }
        });
    }

    protected function loopBulkActions(array $bulkActions)
    {
        collect($bulkActions)->map(function ($bulkAction) {
            if ($bulkAction instanceof ActionGroup) {
                $this->loopActions($bulkAction->getActions());
            } else {
                $this->transformed_bulkActions->push($bulkAction);
            }
        });
    }

    public function callAction(string $name, array $payload = [])
    {
        $this->transformed_actions = collect([]);
        $this->transformed_bulkActions = collect([]);

        $this->loopActions($this->getTableActions());
        $this->loopBulkActions($this->getTableBulkActions());

        $action = $this->transformed_actions->where(function (Action $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        $bulkAction = $this->transformed_bulkActions->where(function (Action $bulkAction) use ($name) {
            return $bulkAction->getName() === $name && $bulkAction->hasHandle();
        })->first();

        if (!$action && !$bulkAction || $action && $bulkAction) throw new ActionNotFoundException();

        if ($action) {
            Validator::make($payload, [
                'id' => ['required', Rule::exists($this->getTableModel(), 'id')]
            ])->validate();

            if (in_array(SoftDeletes::class, class_uses($this->getTableModel()), true)) {
                return $action->getHandleMethod()(app($this->getTableModel())->withTrashed()->find($payload['id']), $payload);
            }

            return $action->getHandleMethod()(app($this->getTableModel())->find($payload['id']), $payload);
        }

        if ($bulkAction) {
            Validator::make($payload, [
                'ids' => ['required', 'array'],
                'ids.*' => [Rule::exists($this->getTableModel(), 'id')]
            ])->validate();

            if (in_array(SoftDeletes::class, class_uses($this->getTableModel()), true)) {
                return $bulkAction->getHandleMethod()(app($this->getTableModel())->withTrashed()->whereIn('id', $payload['ids'])->get(), $payload);
            }


            return $bulkAction->getHandleMethod()(app($this->getTableModel())->whereIn('id', $payload['ids'])->get(), $payload);
        }
    }

    public function setColumnWidths(array $columns)
    {
        $full_width = 100;
        $unused_columns = 0;

        foreach ($columns as $column) {
            if ($column->getWidth()) {
                $full_width -= $column->getWidth();
            } else {
                $unused_columns++;
            }
        }

        foreach ($columns as $column) {
            if (!$column->getWidth()) {
                $column->width($full_width / $unused_columns);
            }
        }
    }

    public function getData(Request $request): array
    {
        if ($request->has("params.{$this->getWidgetId()}")) {
            $this->params = $request->get("params")[$this->getWidgetId()];
        };

        $table = $this->getTable(collect($this->params));

        $this->setColumnWidths($table->getColumns());

        return [
            'bulkActions' => ActionGroupResource::collection($table->getTransformedBulkActions()),
            'columns' => ColumnResource::collection($table->getColumns()),
            'records' => $table->getRecordsInColumns(),
            'filters' => WidgetResource::collection($table->getWidgetsInFilters()),
            'isSearchable' => $table->isSearchable(),
            'pagination' => $table->getPagination()
        ];
    }
}
