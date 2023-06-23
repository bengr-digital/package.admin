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
            'results' => $this->getResults($results)
        ];

        return $this;
    }

    public function getResults(array | Arrayable $results): array
    {
        return collect($results)->map(function (GlobalSearchResult $result) {
            return [
                'title' => $result->getTitle(),
                'description' => $result->getDescription(),
                'redirect' => $result->getRedirect(),
                'icon' => $result->getIcon(),
                'image' => $result->getImage(),
            ];
        })->toArray();
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }
}
