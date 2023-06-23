<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Bengr\Admin\Actions\Action;
use Bengr\Support\Rules\BengrFile;
use Bengr\Support\Rules\BengrFileMax;
use Bengr\Support\Rules\BengrFileMime;
use Bengr\Support\Rules\BengrFileMin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use function Bengr\Support\response;

class FileInput extends Input
{
    use Concerns\CanBeMultiple;
    use Concerns\CanBeSortable;
    use Concerns\InteractsWithMedia;

    protected ?string $widgetName = 'input-file';

    protected ?int $widgetColumnSpan = 12;

    public function save(Model | array &$record, bool $isNew = true, string $name = null): self
    {
        if (!$this->getValue() && !$isNew) {
            $record->clearMediaCollection($this->getName());
        }

        if ($this->isMultiple()) {
            $value = $this->getValue();

            $record->getMedia($this->getName())->each(function ($media) use ($value) {
                if (!collect($value)->contains(fn ($file) => $file['uuid'] == $media->uuid)) {
                    $media->delete();
                }
            });


            foreach ($value as $index => $file) {
                if ($file['temporary']) {
                    $mediaItem = $record->addMediaFromDisk($file['path'], 'local');
                    $mediaItem->order_column = $index;
                    $mediaItem->toMediaCollection($this->getName());
                } else {
                    $mediaItem = Media::findByUuid($file['uuid'] ?? "");
                    $mediaItem->order_column = $index;
                    $mediaItem->save();
                }
            }
        } else {
            if ($this->getValue() && $this->getValue()['temporary']) {
                $record->addMediaFromDisk($this->getValue()['path'], 'local')->toMediaCollection($this->getName());
            }
        }

        return $this;
    }

    public function value($value = null): self
    {
        if ($value instanceof MediaCollection && $value->count()) {
            if ($this->isMultiple()) {
                $value->each(function ($file) {
                    $this->value[] = [
                        'uuid' => $file->uuid,
                        'path' => $file->getUrl(),
                        'temporary' => false,
                        'customProperties' => [
                            'filename' => $file->file_name,
                            'size' => $file->size,
                            'mime' => $file->mime_type,
                        ]
                    ];
                });
            } else {
                $this->value['uuid'] = $value[0]->uuid;
                $this->value['path'] = $value[0]->getUrl();
                $this->value['temporary'] = false;
                $this->value['customProperties'] = [
                    'filename' => $value[0]->file_name,
                    'size' => $value[0]->size,
                    'mime' => $value[0]->mime_type,
                ];
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
            }

            if ($data->getFallbackMediaUrl($this->getName())) {
                return [
                    'uuid' => null,
                    'path' => $data->getFallbackMediaUrl($this->getName()),
                    'temporary' => false,
                ];
            }

            return null;
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
                        $directory = 'tmp/' . Str::random(10);
                        $file = Storage::disk('local')->putFileAs($directory, $file, $file->getClientOriginalName());
                        $value[] = [
                            'uuid' => null,
                            'path' => $file,
                            'temporary' => true
                        ];
                    }
                } else {
                    $directory = 'tmp/' . Str::random(10);
                    $file = Storage::disk('local')->putFileAs($directory, $payload[$this->getName()], $payload[$this->getName()]->getClientOriginalName());

                    $value = [
                        'uuid' => null,
                        'path' => $file,
                        'temporary' => true
                    ];
                }

                $files = Storage::disk('local')->allFiles('tmp');
                $directories = Storage::disk('local')->directories('tmp');

                collect($files)->each(function ($file) {
                    $lastModified = Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file));

                    if ($lastModified->isBefore(Carbon::now()->subHours(12))) {
                        Storage::disk('local')->delete($file);
                    }
                });

                collect($directories)->each(function ($dir) {
                    $filesCount = count(Storage::disk('local')->files($dir));


                    if ($filesCount == 0) {
                        Storage::disk('local')->deleteDirectory($dir);
                    }
                });

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
            'sortable' => $this->isSortable(),
            'rules' => $this->getRules()
        ];
    }
}
