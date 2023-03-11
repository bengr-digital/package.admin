<?php

namespace Bengr\Admin\GlobalSearch;

class GlobalSearchResult
{
    public function __construct(
        public string $title,
        public ?string $description,
        public array $redirect,
        public ?array $icon,
        public ?string $image
    ) {
    }
}
