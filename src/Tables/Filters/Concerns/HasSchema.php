<?php

namespace Bengr\Admin\Tables\Filters\Concerns;

use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Bengr\Admin\Widgets\Widget;

trait HasSchema
{
    protected array $schema = [];

    public function schema(array $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    protected function getFlatSchema(array $widgets): array
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

    protected function getInputs(): array
    {
        return collect($this->getFlatSchema($this->schema))->filter(function (Widget $widget) {
            return $widget instanceof Input;
        })->toArray();
    }

    public function getSchema(): array
    {
        collect($this->getInputs())->each(function (Input $input) {
            if ($this->getName()) {
                $input->name($this->getName() . '.' . $input->getName());
            }
        });

        return $this->schema;
    }
}
