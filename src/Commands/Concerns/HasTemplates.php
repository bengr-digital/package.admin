<?php

namespace Bengr\Admin\Commands\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

enum Template: string
{
    case PLAIN = 'Plain';
    case TABLE = 'Display table of records';
    case CREATE = 'Create new record';
    case EDIT = 'Edit existing recosdfrd';

    public static function getKey(string $value): Template
    {
        return collect(self::toArray())->first(fn ($item) => $item->value == $value);
    }

    public static function getValue(Template $key): string
    {
        return $key->value;
    }

    public static function getValues(): array
    {
        return collect(self::toArray())->map(fn ($item) => $item->value)->values()->toArray();
    }

    private static function toArray(): array
    {
        $reflection = new \ReflectionClass(static::class);

        return $reflection->getConstants();
    }
};

trait HasTemplates
{
    protected function setTemplate(string $template): self
    {
        switch (Template::getKey($template)) {
            case Template::TABLE:
                $model = $this->askForModel('Model (from App\\Models)');
                $columns = $this->askForColumns('Columns', $model);

                break;
            case Template::CREATE:
                $model = $this->askForModel('Model (from App\\Models)');
                $columns = $this->askForColumns('Columns', $model);

                break;
            case Template::EDIT:
                $model = $this->askForModel('Model (from App\\Models)');
                $columns = $this->askForColumns('Columns', $model);

                break;
            default:
                break;
        }

        return $this;
    }

    protected function getTemplates(): array
    {
        return Template::getValues();
    }

    protected function getDefaultTemplate(): string
    {
        return Template::getValue(Template::PLAIN);
    }

    protected function getAllModels(): array
    {
        $modelPath = app_path('Models');

        $modelFiles = File::files($modelPath);

        $models = [];

        foreach ($modelFiles as $modelFile) {
            $className = Str::before($modelFile->getFilename(), '.php');
            $fullyQualifiedClassName = 'App\\Models\\' . $className;

            if (class_exists($fullyQualifiedClassName)) {
                $models[] = $fullyQualifiedClassName;
            }
        }

        return $models;
    }

    protected function getAllModelColumns(string $fullyQualifiedClassName)
    {
        $model = app($fullyQualifiedClassName);
        $columns = $model->getFillable();

        if (in_array('Spatie\\MediaLibrary\\HasMedia', class_implements($model))) {
            foreach ($model->getRegisteredMediaCollections() as $collection) {
                $columns[] = $collection->name;
            }
        }

        return $columns;
    }

    protected function askForModel(string $label): string
    {
        return $this->validateModel(fn () => $this->anticipate('Model (from App\\Models)', collect($this->getAllModels())->map(fn ($item) => Str::afterLast($item, 'App\\Models\\'))->toArray()));
    }

    protected function askForColumns(string $label, string $model): array
    {
        $columns = [];

        if (class_exists($model)) {
            $columns = $this->choice('Columns', array_merge(['*'], $this->getAllModelColumns($model)), null, null, true);
        }

        if (collect($columns)->contains(fn ($item) => $item == '*')) {
            $columns = $this->getAllModelColumns($model);
        }

        return $columns;
    }

    protected function validateModel(\Closure $callback): string
    {
        $model = $callback();

        if (!$model) {
            $this->error('The model is required');
        }

        if ($model && !class_exists('App\\Models\\' . $model)) {
            $this->error("The $model model does not exist in App\\Models");
            $continue = $this->confirm('Continue anyway? (Will be created without columns)', false);

            if ($continue) return 'App\\Models\\' . $model;
        }

        if ($model && class_exists('App\\Models\\' . $model)) {
            return 'App\\Models\\' . $model;
        }

        return $this->validateModel($callback);
    }
}
