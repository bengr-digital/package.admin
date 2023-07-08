<?php

namespace Bengr\Admin\Http\Resources;

use Bengr\Admin\Facades\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
{
    public static $wrap = '';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $atBuilderWidgetsPath = $request->getPathInfo() == Admin::getApiRouteUrl('widgets');

        if ($atBuilderWidgetsPath) {
            return [
                'id' => $this->getWidgetId(),
                'type' => $this->getWidgetName(),
                'columnSpan' => $this->getWidgetColumnSpan(),
                'lazyload' => $this->getLazyload(),
                'props' => !$this->getWithoutProps() ? $this->getData($request) : [],
            ];
        } else {
            return [
                'id' => $this->getWidgetId(),
                'type' => $this->getWidgetName(),
                'columnSpan' => $this->getWidgetColumnSpan(),
                'lazyload' => $this->getLazyload(),
                'props' => !$this->getWithoutProps() && !$this->getLazyload() ? $this->getData($request) : [],
            ];
        }
    }
}
