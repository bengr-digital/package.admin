<?php

namespace Bengr\Admin\Pages\Concerns;

use Illuminate\Support\Str;

trait Translatable
{
    public function getTitle(): string
    {
        $title = $this->title ?? (string) Str::of(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();

        return __($title);
    }

    public function getDescription(): ?string
    {
        return __($this->description);
    }
}
