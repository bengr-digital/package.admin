<?php

namespace Bengr\Admin\Forms;

use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
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

    public function validate()
    {
        $rules = collect($this->getInputs())->mapWithKeys(function (Input $input) {
            return $input->getRules([
                'record' => $this->getRecord()
            ]);
        })->toArray();

        Validator::make($this->getValue(), $rules)->validate();
    }

    public function getSchema(): array
    {
        return $this->formResource->getCachedFormSchema();
    }

    public function getRecord(): ?Model
    {
        return $this->formResource->getRecord();
    }


    public function getModel(): ?string
    {
        return $this->formResource->getModel();
    }

    public function getInputs(): array
    {
        return $this->formResource->getCachedFormInputs();
    }

    public function getValue(?string $name = null)
    {
        return $this->formResource->getCachedValue($name);
    }

    public function getInput(string $name): ?Input
    {
        return $this->formResource->getCachedFormInput($name);
    }

    public function save(): bool
    {
        DB::transaction(function () {
            $record = $this->getRecord() ?? app($this->getModel());

            collect($this->getInputs())->each(function (Input $input) use ($record) {
                $input->save($record, !$this->getRecord());
            });

            $record->save();
        });

        return true;
    }
}
