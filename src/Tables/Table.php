<?php

namespace Bengr\Admin\Tables;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Resources\ActionGroupResource;
use Bengr\Admin\Http\Resources\ActionResource;
use Bengr\Admin\Tables\Columns\Column;
use Bengr\Admin\Tables\Contracts\HasTable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;

class Table
{
    protected HasTable $tableResource;

    protected SupportCollection $params;

    final public function __construct(HasTable $tableResource, SupportCollection $params)
    {
        $this->tableResource = $tableResource;
        $this->params = $params;
    }

    public static function make(HasTable $tableResource, SupportCollection $params): static
    {
        return app(static::class, ['tableResource' => $tableResource, 'params' => $params]);
    }

    public function getColumns(): array
    {
        return $this->tableResource->getCachedTableColumns();
    }

    public function getActions(?Model $record = null): array
    {
        return $this->tableResource->getCachedTableActions($record);
    }

    public function getActionOnClick(): ?Action
    {
        return $this->tableResource->getCachedTableActionOnClick();
    }

    public function getFilters(): array
    {
        return $this->tableResource->getCachedTableFilters();
    }

    public function getBulkActions(): array
    {
        return $this->tableResource->getCachedTableBulkActions();
    }

    public function getRecords(): Collection | Paginator
    {
        return $this->tableResource->getTableRecords($this->params);
    }

    public function getWidgetsInFilters(): array
    {
        $widgets = [];

        foreach ($this->getFilters() as $filter) {
            $widgets = [...$widgets, ...$filter->getSchema()];
        }

        return $widgets;
    }

    public function isSearchable(): bool
    {
        return collect($this->getColumns())->contains(fn (Column $column) => $column->isSearchable());
    }

    public function getRecordsInColumns(): SupportCollection
    {
        $records_in_columns = collect();

        foreach ($this->getRecords() as $record) {
            $record_in_column = collect([
                'id' => $record->id,
            ]);

            $columns = [];


            foreach ($this->getColumns() as $column) {
                $columns[] = [
                    'name' => $column->getName(),
                    'value' => $column->getValue($record),
                    'props' => $column->getProps($record)
                ];
            }

            $record_in_column->put('columns', $columns);

            if ($this->getActionOnClick()) {
                $actionOnClick = (clone $this->getActionOnClick())->record($record);

                if ($actionOnClick->hasHandle() && !$actionOnClick->getHandleWidgetId()) {
                    $actionOnClick->handle($actionOnClick->getHandleMethod(), $this->tableResource->getWidgetId() ?? null);
                }

                if ($actionOnClick->getModalCodeId() && !$actionOnClick->getModalId()) {
                    $modal = collect(BengrAdmin::getCurrentPage()->getTransformedModals())->first(fn ($modal) => $modal->getCodeId() == $actionOnClick->getModalCodeId());

                    if ($modal) {
                        $actionOnClick->modal($modal->getId(), $actionOnClick->getModalEvent());
                    }
                }

                $record_in_column->put('actionOnClick', ActionResource::make($actionOnClick));
            } else {
                $record_in_column->put('actionOnClick', null);
            }

            $actions = collect($this->getActions())->map(function ($action) {
                return clone $action;
            })->each(function ($action) use ($record) {
                if ($action->hasHandle() && !$action->getHandleWidgetId()) {
                    $action->handle($action->getHandleMethod(), $this->tableResource->getWidgetId() ?? null);
                }

                if ($action->getModalCodeId() && !$action->getModalId()) {
                    $modal = collect(BengrAdmin::getCurrentPage()->getTransformedModals())->first(fn ($modal) => $modal->getCodeId() == $action->getModalCodeId());

                    if ($modal) {
                        $action->modal($modal->getId(), $action->getModalEvent());
                    }
                }

                $action->record($record);
            });


            $record_in_column->put('actions', ActionGroupResource::collection($actions));

            $records_in_columns->push($record_in_column);
        }


        return $records_in_columns;
    }

    public function getTransformedBulkActions(): array
    {
        return collect($this->getBulkActions())->map(function ($bulkAction) {
            if ($bulkAction->hasHandle() && !$bulkAction->getHandleWidgetId()) {
                $bulkAction->handle($bulkAction->getHandleMethod(), $this->tableResource->getWidgetId() ?? null);
            }

            return $bulkAction;
        })->toArray();
    }

    public function getPagination(): SupportCollection
    {
        return collect([
            'total' => $this->getRecords()->total(),
            'perPage' => $this->getRecords()->perPage(),
            'currentPage' => $this->getRecords()->currentPage(),
            'lastPage' => $this->getRecords()->lastPage(),
            'pageName' => $this->getRecords()->getPageName(),
        ]);
    }
}
