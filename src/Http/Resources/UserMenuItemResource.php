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
            'icon' => $this->getIcon(),
            'route' => [
                'name' => $this->getRouteName(),
                'url' => $this->getRouteUrl(),
            ]
        ];
    }
}
