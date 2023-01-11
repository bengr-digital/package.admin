<?php

namespace Bengr\Admin\Actions\Concerns;

trait CanHandleAction
{
    protected ?\Closure $handleMethod = null;

    protected ?int $handleWidgetId = null;

    public function handle(\Closure $method, ?int $widgetId = null): static
    {
        $this->handleMethod = $method;
        $this->handleWidgetId = $widgetId;

        return $this;
    }

    public function hasHandle()
    {
        return $this->handleMethod ? true : false;
    }

    public function getHandleMethod(): ?\Closure
    {
        return $this->handleMethod;
    }

    public function getHandleWidgetId(): ?int
    {
        return $this->handleWidgetId;
    }
}
