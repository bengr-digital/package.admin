<?php

namespace Bengr\Admin\GlobalSearch;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class GlobalSearchResults
{
    protected Collection $categories;

    final public function __construct()
    {
        $this->categories = Collection::make();
    }

    public static function make(): static
    {
        return new static();
    }

    public function category(?string $name = '', array | Arrayable $results = []): static
    {
        $this->categories[] = [
            'label' => $name,
            'results' => $results
        ];

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }
}
