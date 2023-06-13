<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
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
            'id' => $this->getWidgetId(),
            'type' => $this->getWidgetName(),
            'columnSpan' => $this->getWidgetColumnSpan(),
            'lazyload' => $this->getLazyload(),
            'props' => !$this->getWithoutProps() ? $this->getData($request) : [],
        ];
    }
}
