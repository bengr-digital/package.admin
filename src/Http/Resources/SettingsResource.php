<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
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
            'phone' => $this->phone,
            'email' => $this->email,
            'billing' => $this->billing()->get(['name', 'country', 'city', 'zipcode', 'street', 'cin', 'tin']),
            'socials' => $this->socials()->get(['name', 'url']),
            'languages' => $this->languages()->get(['code', 'is_default']),
        ];
    }
}
