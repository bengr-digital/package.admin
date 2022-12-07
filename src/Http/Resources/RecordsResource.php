<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecordsResource extends JsonResource
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
            'records' => $this->getTable($request)->getRecordsInColumns(),
            'pagination' => $this->getTable($request)->getPagination()
        ];
    }
}
