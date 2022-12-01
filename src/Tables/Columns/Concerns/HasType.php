<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

use Illuminate\Support\Str;

trait HasType
{
    public function getType(): string
    {
        return Str::of(class_basename(static::class))->kebab()->replace('-column', '');
    }
}
