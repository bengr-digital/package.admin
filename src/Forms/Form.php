<?php

namespace Bengr\Admin\Forms;

use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Support\Collection as SupportCollection;
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
            return [$input->getName() => $input];
        })->map(function (Input $input) {
            return $input->getRules();
        })->toArray();

        Validator::make($payload, $rules)->validate();
    }

    public function getSchema(): array
    {
        return $this->formResource->getCachedFormSchema();
    }

    public function getInputs(): array
    {
        return $this->formResource->getCachedFormInputs();
    }
}
