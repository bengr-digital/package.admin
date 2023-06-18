<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Bengr\Admin\Concerns\EvaluatesClosures;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Http\Request;

class Input extends Widget
{
    use EvaluatesClosures;
    use Concerns\HasId;
    use Concerns\HasType;
    use Concerns\HasName;
    use Concerns\HasLabel;
    use Concerns\HasPlaceholder;
    use Concerns\HasRules;
    use Concerns\HasValue;
    use Concerns\HasDefaultValue;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanBeReadonly;
    use Concerns\CanBeRequired;
    use Concerns\CanSave;
    use Concerns\InteractsWithTableQuery;

    protected ?string $widgetName = 'input';

    protected ?int $widgetColumnSpan = 12;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(string $name): static
    {
        return app(static::class, ['name' => $name]);
    }

    public function getData(Request $request): array
    {
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'placeholder' => $this->getPlaceholder(),
            'value' => $this->getValue(),
            'required' => $this->isRequired(),
            'disabled' => $this->isDisabled(),
            'hidden' => $this->isHidden(),
            'readonly' => $this->isReadonly(),
            'rules' => $this->getRules()
        ];
    }
}
