<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NavigationItemResource extends JsonResource
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
            "label" => $this->getLabel(),
            "icon" => $this->getIcon(),
            "activeIcon" => $this->getActiveIcon(),
            "badge" => $this->getBadge(),
            "badgeColor" => $this->getBadgeColor(),
            "route" => [
                "name" => $this->getRouteName(),
                "url" => $this->getRouteUrl()
            ],
            "children" => NavigationItemResource::collection($this->getChildren())
        ];
    }
}
