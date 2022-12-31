<?php

namespace Bengr\Admin\Tables;

use Bengr\Admin\Http\Resources\ActionGroupResource;
use Bengr\Admin\Tables\Contracts\HasTable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as SupportCollection;

class Table
{
    protected HasTable $tableResource;

    protected Request $request;

    final public function __construct(HasTable $tableResource, Request $request)
    {
        $this->tableResource = $tableResource;
        $this->request = $request;
    }

    public static function make(HasTable $tableResource, Request $request): static
    {
        return app(static::class, ['tableResource' => $tableResource, 'request' => $request]);
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

    public function getRecords(): Collection | Paginator
    {
        return $this->tableResource->getTableRecords($this->request);
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
                    'value' => Arr::get($record, $column->getName()),
                    'params' => []
                ];
            }

            $record_in_column->put('columns', $columns);

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
