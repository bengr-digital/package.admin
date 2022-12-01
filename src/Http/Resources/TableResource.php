<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'actions' => ActionGroupResource::collection($this->getActions()),
            'bulkActions' => ActionGroupResource::collection($this->getBulkActions()),
            'columns' => ColumnResource::collection($this->getColumns()),
            'records' => TableRecordResource::collection($this->getRecords())
        ];
    }
}
