<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $atBuilderWidgetsPath = $request->getPathInfo() == (config('admin.routes.url') . config('admin.routes.routes.builder.url') . config('admin.routes.routes.builder.routes.widgets.url'));

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
