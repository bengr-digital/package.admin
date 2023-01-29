<?php

namespace Bengr\Admin\Forms;

use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Form
{
    protected HasForm $formResource;

    protected SupportCollection $params;

    final public function __construct(HasForm $formResource, SupportCollection $params)
    {
        $this->formResource = $formResource;
        $this->params = $params;
    }

    public static function make(HasForm $formResource, SupportCollection $params): static
    {
        return app(static::class, ['formResource' => $formResource, 'params' => $params]);
    }

    public function validate(array $payload)
    {
        $rules = collect($this->getInputs())->mapWithKeys(function (Input $input) {
            return $input->getRules();
        })->toArray();
        $validator = Validator::make($payload, $rules);
        $validator->validate();

        return $validator->validated();
    }

    public function getSchema(): array
    {
        return $this->formResource->getCachedFormSchema();
    }

    public function getInputs(): array
    {
        return $this->formResource->getCachedFormInputs();
    }

    public function getInput(string $name): ?Input
    {
        return $this->formResource->getCachedFormInput($name);
    }
}
