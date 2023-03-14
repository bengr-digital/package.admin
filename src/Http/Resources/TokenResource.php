<?php

namespace Bengr\Admin\Http\Resources;

use App\Models\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
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
            'token' => [
                'name' => $this->getName(),
                'access_token' => $this->getAccessToken(),
                'refresh_token' => $this->getRefreshToken(),
            ]
        ];
    }
}
