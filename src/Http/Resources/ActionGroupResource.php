<?php

namespace Bengr\Admin\Http\Resources;

use Bengr\Admin\Actions\ActionGroup;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource instanceof ActionGroup) {
            return [
                'icon' => $this->getIcon(),
                'color' => $this->getColor(),
                'size' => $this->getSize(),
                'tooltip' => $this->getTooltip(),
                'isHidden' => $this->isHidden(),
                'actions' => ActionResource::collection($this->getActions())
            ];
        }

        return ActionResource::make($this);
    }
}
