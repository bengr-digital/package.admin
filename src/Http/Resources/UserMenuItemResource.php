<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMenuItemResource extends JsonResource
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
            'label' => $this->getLabel(),
            'icon' => [
                'name' => $this->getIconName(),
                'type' => $this->getIconType(),
                'activeName' => $this->getActiveIconName(),
                'activeType' => $this->getActiveIconType(),
            ],
            'route' => [
                'name' => $this->getRouteName(),
                'url' => $this->getRouteUrl(),
            ]
        ];
    }
}
