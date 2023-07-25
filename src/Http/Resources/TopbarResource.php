<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Bengr\Admin\Facades\Admin;

class TopbarResource extends JsonResource
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
            'userMenu' => UserMenuResource::make(Admin::getUserMenuItems()),
            'notifications' => null,
            'globalSearch' => Admin::hasGlobalSearch()
        ];
    }
}
