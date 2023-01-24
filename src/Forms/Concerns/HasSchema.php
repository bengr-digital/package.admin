<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Bengr\Admin\Widgets\ActionWidget;
use Bengr\Admin\Widgets\Widget;

trait HasSchema
{
    protected array $cachedFormSchema = [];
    protected array $cachedFormInputs = [];

    public function getCachedFormSchema(): array
    {
        $this->cachedFormSchema = [];

        foreach ($this->getFormSchema() as $schema) {
            $this->cachedFormSchema[] = $schema;
        }

        return $this->cachedFormSchema;
    }

    public function getCachedFormInputs(): array
    {
        $this->cachedFormInputs = [];

        foreach ($this->getFormInputs() as $input) {
            $this->cachedFormInputs[] = $input;
        }

        return $this->cachedFormInputs;
    }

    protected function getFormSchema(): array
    {
        return [];
    }

    protected function getFlatFormSchema(array $widgets): array
    {
        $flat = [];

        foreach ($widgets as $widget) {
            if ($widget->hasWidgets()) {
                $flat[] = $widget;
                $flat = array_merge($flat, $this->getFlatFormSchema($widget->getWidgets()));
            } else {
                $flat[] = $widget;
            }
        }

        return $flat;
    }

    protected function getFormInputs(): array
    {
        return collect($this->getFlatFormSchema($this->getFormSchema()))->filter(function (Widget $widget) {
            return $widget instanceof Input;
        })->toArray();
    }

    protected function getFormActions(): array
    {
        return collect($this->getFlatFormSchema($this->getFormSchema()))->filter(function (Widget $widget) {
            return $widget instanceof ActionWidget;
        })->toArray();
    }
}
