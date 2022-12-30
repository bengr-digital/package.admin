<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModalResource extends JsonResource
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
            'id' => $this->getId(),
            'type' => $this->getType(),
            'direction' => $this->getDirection(),
            'heading' => $this->getHeading(),
            'subheading' => $this->getSubheading(),
            'hasCross' => $this->hasCross(),
            'widgets' => WidgetResource::collection($this->getWidgets()),
            'actions' => ActionGroupResource::collection($this->getActions()),
        ];
    }
}
