<?php

namespace Bengr\Admin\Forms\Concerns;

trait HasSchema
{
    protected array $cachedFormSchema = [];

    public function getCachedFormSchema(): array
    {
        $this->cachedFormSchema = [];

        foreach ($this->getFormSchema() as $schema) {
            $this->cachedFormSchema[] = $schema;
        }

        return $this->cachedFormSchema;
    }

    protected function getFormSchema(): array
    {
        return [];
    }

    public function getFlatFormSchema(): array
    {
        return [];
    }
}
