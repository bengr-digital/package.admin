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

    protected ?string $name = 'table';

    protected int $columnSpan = 12;

    protected string $model;

    protected array $columns = [];

    protected array $actions = [];

    protected array $bulkActions = [];

    protected array $params = [];

    protected $transformed_actions;

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

    public function callAction(string $name, array $payload = [])
    {
        $this->transformed_actions = collect([]);

        $this->loopActions($this->getTableActions());

        $action = $this->transformed_actions->where(function (Action $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action) return response()->throw(ActionNotFoundException::class);

        Validator::make($payload, [
            'id' => ['required', Rule::exists($this->getTableModel(), 'id')]
        ])->validate();

        return $action->getHandleMethod()(app($this->getTableModel())->find($payload['id']), $payload);
    }


    public function getData(Request $request): array
    {
        if ($request->has("params.{$this->getId()}")) {
            $this->params = $request->get("params")[$this->getId()];
        };

        $table = $this->getTable(collect($this->params));

        return [
            'bulkActions' => ActionGroupResource::collection($table->getBulkActions()),
            'columns' => ColumnResource::collection($table->getColumns()),
            'records' => $table->getRecordsInColumns(),
            'pagination' => $table->getPagination()
        ];
    }
}
