<?php

namespace Bengr\Admin\Http\Resources;

use Bengr\Admin\Facades\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class NavigationResource extends JsonResource
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
            "visible" => true,
            "groups" => NavigationGroupResource::collection($this)
        ];
    }
}
