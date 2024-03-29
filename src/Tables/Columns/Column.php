<?php

namespace Bengr\Admin\Tables\Columns;

use Bengr\Admin\Concerns\EvaluatesClosures;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Column
{
    use EvaluatesClosures;
    use Concerns\CanAggregateRelatedModels;
    use Concerns\CanBeSearchable;
    use Concerns\CanBeSortable;
    use Concerns\CanBeHidden;
    use Concerns\CanBeDisabled;
    use Concerns\HasName;
    use Concerns\HasLabel;
    use Concerns\HasType;
    use Concerns\HasWidth;
    use Concerns\InteractsWithTableQuery;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);

        return $static;
    }

    public function getValue(Model $record)
    {
        return Arr::get($record, $this->getName());
    }

    public function getProps(Model $record): array
    {
        return [];
    }
}
