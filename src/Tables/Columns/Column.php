<?php

namespace Bengr\Admin\Tables\Columns;

class Column
{
    use Concerns\CanBeSearchable;
    use Concerns\CanBeSortable;
    use Concerns\HasName;
    use Concerns\HasLabel;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);

        return $static;
    }
}
