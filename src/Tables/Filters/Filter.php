<?php

namespace Bengr\Admin\Tables\Filters;

use Bengr\Admin\Concerns\EvaluatesClosures;

class Filter
{
    use EvaluatesClosures;
    use Concerns\CanBeHidden;
    use Concerns\HasName;
    use Concerns\HasSchema;
    use Concerns\InteractsWithTableQuery;

    final public function __construct(?string $name)
    {
        $this->name($name);
    }

    public static function make(?string $name = null): static
    {
        $static = app(static::class, ['name' => $name]);

        return $static;
    }
}
