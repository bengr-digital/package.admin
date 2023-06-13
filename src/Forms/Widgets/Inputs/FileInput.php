<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Bengr\Admin\Actions\Action;
use Bengr\Support\Rules\BengrFile;
use Bengr\Support\Rules\BengrFileMax;
use Bengr\Support\Rules\BengrFileMime;
use Bengr\Support\Rules\BengrFileMin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

use function Bengr\Support\response;

class FileInput extends Input
{
    use Concerns\CanBeMultiple;

    protected ?string $widgetName = 'input-file';

    protected ?int $widgetColumnSpan = 12;

    public function value($value = null): self
    {
        if ($value instanceof MediaCollection && $value->count()) {
            if ($this->isMultiple()) {
                $value->each(function ($file) {
                    $this->value[] = [
                        'uuid' => $file->uuid,
                        'path' => $file->getUrl(),
                        'temporary' => false,
                    ];
                });
            } else {
                $this->value['uuid'] = $value[0]->uuid;
                $this->value['path'] = $value[0]->getUrl();
                $this->value['temporary'] = false;
            };
        } else {
            if ($value && array_key_exists(0, $value) && !$this->isMultiple()) {
                $this->value = $value[0];
            } else {
                $this->value = $value;
            }
        }

        return $this;
    }

    public function getValueFromData(array | Model | null $data)
    {
        if ($data instanceof Model) {
            $mediaCollection = $data->getMedia($this->getName());

            if ($mediaCollection->count()) {
                return $mediaCollection;
            } else {
                return [
                    'uuid' => null,
                    'path' => $data->getFallbackMediaUrl($this->getName()),
                    'temporary' => false,
                ];
            }
        }

        if (!$data) return $this->getValue();

        return Arr::get($data, $this->getName());
    }

    public function getActions(): array
    {
        return [
            Action::make('input')->handle(function ($payload) {
                $value = [];

                Validator::make($payload, $this->getUploadedFileRules())->validate();

                if ($this->isMultiple()) {
                    foreach ($payload[$this->getName()] as $file) {
                        $file = Storage::disk('local')->put("/tmp", $file);
                        $value[] = [
                            'uuid' => null,
                            'path' => $file,
                            'temporary' => true
                        ];
                    }
                } else {
                    $file = Storage::disk('local')->put("/tmp", $payload[$this->getName()]);

                    $value = [
                        'uuid' => null,
                        'path' => $file,
                        'temporary' => true
                    ];
                }


                return response()->json([
                    'value' => $value
                ]);
            })
        ];
    }

    protected function getUploadedFileRules(): array
    {

        if ($this->isMultiple()) {

            return [
                $this->getName() => in_array('required', $this->rules) ? ['required', 'array'] : ['array'],
                "{$this->getName()}.*" => collect($this->getRules()["{$this->getName()}.*"])->map(function ($rule) {
                    if ($rule instanceof BengrFile) {
                        return 'file';
                    }

                    if ($rule instanceof BengrFileMax) {
                        $size = $rule->getSize();

                        return "max:{$size}";
                    }

                    if ($rule instanceof BengrFileMin) {
                        $size = $rule->getSize();

                        return "min:{$size}";
                    }

                    if ($rule instanceof BengrFileMime) {
                        $mimes = implode(',', $rule->getMimes());

                        return "mimes:{$mimes}";
                    }

                    return $rule;
                })->toArray(),

            ];
        }

        return [
            $this->getName() => collect($this->getRules()[$this->getName()])->map(function ($rule) {
                if ($rule instanceof BengrFile) {
                    return 'file';
                }

                if ($rule instanceof BengrFileMax) {
                    $size = $rule->getSize();

                    return "max:{$size}";
                }

                if ($rule instanceof BengrFileMin) {
                    $size = $rule->getSize();

                    return "min:{$size}";
                }

                if ($rule instanceof BengrFileMime) {
                    $mimes = implode(',', $rule->getMimes());

                    return "mimes:{$mimes}";
                }

                return $rule;
            })->toArray(),
        ];
    }


    public function getRules(array $parameters = []): array
    {
        if ($this->isMultiple()) {
            return [
                $this->getName() => in_array('required', $this->evaluate($this->rules, $parameters)) ? ['required', 'array'] : ['array'],
                "{$this->getName()}.*" => $this->evaluate($this->rules, $parameters),
            ];
        }

        return [
            $this->getName() => $this->evaluate($this->rules, $parameters)
        ];
    }

    public function getType(): ?string
    {
        return 'file';
    }

    public function getData(Request $request): array
    {
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'required' => $this->isRequired(),
            'disabled' => $this->isDisabled(),
            'hidden' => $this->isHidden(),
            'multiple' => $this->isMultiple(),
            'readonly' => $this->isReadonly(),
            'rules' => $this->getRules()
        ];
    }
}
