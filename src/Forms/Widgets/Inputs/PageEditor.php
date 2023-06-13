<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PageEditor extends Input
{
    protected ?string $widgetName = 'page-editor';

    protected ?int $widgetColumnSpan = 12;

    protected array $columns = ['id', 'code', 'text'];

    protected ?string $srcName = 'path';

    protected ?string $src = null;

    public static function make(string $name): static
    {
        return app(static::class, ['name' => $name]);
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function src(string $srcName): self
    {
        $this->srcName = $srcName;

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->src ?? config('app.frontend_url');
    }

    public function getValueFromData(array|Model|null $data)
    {
        try {
            if ($data instanceof Model) {
                $this->src = config('app.frontend_url') . Arr::get($data, $this->srcName);

                return Arr::get($data, $this->getName())->map(function ($item) {
                    $final = collect([]);

                    foreach ($this->getColumns() as $column) {
                        $final->put($column, $item[$column]);
                    }

                    return $final;
                })->toArray();
            }

            return Arr::get($data, $this->getName());
        } catch (\Throwable $e) {
            return $this->getValue();
        }
    }

    public function getRules(array $parameters = []): array
    {
        return [
            $this->getName() => in_array('required', $this->evaluate($this->rules, $parameters)) ? ['required', 'array'] : ['array'],
            "{$this->getName()}.*.id" => array_merge($this->evaluate($this->rules, $parameters), [Rule::exists('subpage_contents', 'id')]),
            "{$this->getName()}.*.code" => array_merge($this->evaluate($this->rules, $parameters), []),
            "{$this->getName()}.*.text" => array_merge($this->evaluate($this->rules, $parameters), []),
        ];
    }

    public function getType(): ?string
    {
        return 'iframe';
    }

    public function getData(Request $request): array
    {
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'src' => $this->getSrc(),
            'value' => $this->getValue(),
            'disabled' => $this->isDisabled(),
            'hidden' => $this->isHidden(),
            'readonly' => $this->isReadonly(),
            'rules' => $this->getRules()
        ];
    }
}
