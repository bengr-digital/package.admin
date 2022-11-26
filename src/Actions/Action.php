<?php

namespace Bengr\Admin\Actions;

use Illuminate\Support\Str;

class Action
{
    use Concerns\HasName;
    use Concerns\HasLabel;
    use Concerns\HasIcon;
    use Concerns\HasColor;
    use Concerns\HasSize;
    use Concerns\HasTooltip;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanBeOutlined;
    use Concerns\CanOpenModal;
    use Concerns\CanOpenUrl;
    use Concerns\CanRedirect;
    use Concerns\CanCallApi;

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
