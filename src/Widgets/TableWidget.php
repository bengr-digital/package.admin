<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Actions\ActionGroup;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Http\Resources\ActionGroupResource;
use Bengr\Admin\Http\Resources\ColumnResource;
use Bengr\Admin\Tables\Concerns\InteractsWithTable;
use Bengr\Admin\Tables\Contracts\HasTable;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function Bengr\Support\response;

class TableWidget extends Widget implements HasTable
{
    use InteractsWithTable;

    protected ?string $widgetName = 'table';

    protected ?int $widgetColumnSpan = 12;

    protected string $model;

    protected array $columns = [];

    protected array $actions = [];

    protected array $bulkActions = [];

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

    public function bulkActions(array $bulkActions): self
    {
        $this->bulkActions = $bulkActions;

        return $this;
    }

    protected function getTableColumns()
    {
        return $this->columns ?? [];
    }

    protected function getTableActions(): array
    {
        return $this->actions ?? [];
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

        if (!$action && !$bulkAction || $action && $bulkAction) return response()->throw(ActionNotFoundException::class);

        if ($action) {
            Validator::make($payload, [
                'id' => ['required', Rule::exists($this->getTableModel(), 'id')->where('deleted_at', null)]
            ])->validate();

            return $action->getHandleMethod()(app($this->getTableModel())->find($payload['id']), $payload);
        }

        if ($bulkAction) {
            Validator::make($payload, [
                'ids' => ['required', 'array'],
                'ids.*' => [Rule::exists($this->getTableModel(), 'id')->where('deleted_at', null)]
            ])->validate();

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

        dd($this->params);

        $table = $this->getTable(collect($this->params));

        $this->setColumnWidths($table->getColumns());

        return [
            'bulkActions' => ActionGroupResource::collection($table->getBulkActions()),
            'columns' => ColumnResource::collection($table->getColumns()),
            'records' => $table->getRecordsInColumns(),
            'pagination' => $table->getPagination()
        ];
    }
}
