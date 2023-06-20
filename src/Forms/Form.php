<?php

namespace Bengr\Admin\Forms;

use Bengr\Admin\Forms\Contracts\HasForm;
use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function save(array $ignore = [], array $replace = []): bool
    {
        DB::transaction(function () use ($ignore, $replace) {
            $query = $this->getRecord()->query() ?? app($this->getModel())->query();

            collect($this->getInputs())->each(function (Input $input) use ($query) {
                $input->applyEagerLoading($query);
            });

            $record = $query->first();

            collect($this->getValue())->each(function ($value, $key) use ($record, $ignore, $replace) {
                if (in_array($key, $ignore)) return;

                if ($record->isRelation($key)) {
                    $relationData = [];
                    $inputs = collect($this->getInputs())->filter(fn (Input $input) => Str::of($input->getName())->startsWith("{$key}."))->toArray();

                    foreach ($inputs as $input) {
                        $input->save($relationData, !$this->getRecord(), Str::of($input->getName())->afterLast('.'));
                    }

                    if ($record->$key) {
                        $record->{$key}()->update($relationData);
                    } else {
                        $record->{$key}()->create($relationData);
                    }
                } else {
                    if (key_exists($key, $replace)) {
                        $this->getInput($key)->save($record, !$this->getRecord(), $replace[$key]);
                    } else {
                        $this->getInput($key)->save($record, !$this->getRecord());
                    }
                }
            });


            $record->save();
        });

        return true;
    }
}
