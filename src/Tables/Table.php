<?php

namespace Bengr\Admin\Tables;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Http\Resources\ActionGroupResource;
use Bengr\Admin\Http\Resources\ActionResource;
use Bengr\Admin\Tables\Columns\Column;
use Bengr\Admin\Tables\Contracts\HasTable;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
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

    public function getActionOnClick(?Model $record = null): ?Action
    {
        return $this->tableResource->getCachedTableActionOnClick($record);
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

            $record_in_column->put('actionOnClick', $this->getActionOnClick($record) ? ActionResource::make($this->getActionOnClick($record)) : null);

            $record_in_column->put('actions', ActionGroupResource::collection($this->getActions($record)));

            $records_in_columns->push($record_in_column);
        }


        return $records_in_columns;
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
