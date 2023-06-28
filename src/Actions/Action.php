<?php

namespace Bengr\Admin\Actions;

use Bengr\Admin\Concerns\EvaluatesClosures;
use Illuminate\Support\Str;

class Action
{
    use EvaluatesClosures;
    use Concerns\HasName;
    use Concerns\HasConfirm;
    use Concerns\HasLabel;
    use Concerns\HasIcon;
    use Concerns\HasColor;
    use Concerns\HasSize;
    use Concerns\HasRoute;
    use Concerns\HasRedirect;
    use Concerns\HasTooltip;
    use Concerns\HasParams;
    use Concerns\HasType;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanBeDownload;
    use Concerns\CanHandleModal;
    use Concerns\CanHandleAction;
    use Concerns\InteractsWithRecord;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(?string $name = null): static
    {
        return app(static::class, [
            'name' => $name ?? Str::of(class_basename(static::class))->kebab()->slug()
        ]);
    }
}
