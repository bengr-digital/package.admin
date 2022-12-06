<?php

namespace Bengr\Admin\Tables;

use Bengr\Admin\Http\Resources\ActionGroupResource;
use Bengr\Admin\Tables\Contracts\HasTable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as SupportCollection;

class Table
{
    protected HasTable $tableResource;

    final public function __construct($tableResource)
    {
        $this->tableResource = $tableResource;
    }

    public static function make(HasTable $tableResource): static
    {
        return app(static::class, ['tableResource' => $tableResource]);
    }

    public function getColumns(): array
    {
        return $this->tableResource->getCachedTableColumns();
    }

    public function getActions(): array
    {
        return $this->tableResource->getCachedTableActions();
    }

    public function getBulkActions(): array
    {
        return $this->tableResource->getCachedTableBulkActions();
    }

    public function getRecords(?int $page = 1): Collection | Paginator
    {
        return $this->tableResource->getTableRecords($page);
    }

    public function getRecordsInColumns(?int $page = 1): SupportCollection
    {
        $records_in_columns = collect();

        foreach ($this->getRecords($page) as $record) {
            $record_in_column = collect([
                'id' => $record->id
            ]);

            foreach ($this->getColumns() as $column) {
                $record_in_column->put($column->getName(), [
                    'value' => Arr::get($record, $column->getName()),
                    'params' => []
                ]);
            }

            $record_in_column->put('actions', ActionGroupResource::collection($this->getActions()));


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
