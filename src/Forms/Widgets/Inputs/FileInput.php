<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Bengr\Admin\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use finfo;

use function Bengr\Support\response;

class FileInput extends Input
{
    use Concerns\CanBeMultiple;

    protected ?string $widgetName = 'input-file';

    protected int $widgetColumnSpan = 12;

    public function transformValue()
    {
        return $this->value;
        return collect($this->value)->map(function ($file) {
            $file_path = storage_path('app/' . $file['path']);
            $finfo = new finfo(FILEINFO_MIME_TYPE);

            if (Storage::disk('local')->exists($file['path'])) {

                return new UploadedFile(
                    $file_path,
                    $file['path'],
                    $finfo->file($file_path),
                    0,
                    false
                );
            }

            return null;
        })->toArray();
    }

    public function value($value = null): self
    {
        if ($value instanceof MediaCollection && $value->count()) {
            if ($this->isMultiple()) {
                $value->each(function ($file) {
                    $this->value[] = [
                        'path' => $file->getUrl(),
                        'temporary' => false,
                    ];
                });
            } else {
                $this->value['path'] = $value[0]->getUrl();
                $this->value['temporary'] = false;
            };
        } else {
            $this->value = $value;
        }

        return $this;
    }

    public function getActions(): array
    {
        return [
            Action::make('upload')->handle(function ($payload) {
                $value = [];

                Validator::make($payload, $this->getRules())->validate();


                foreach ($payload[$this->getName()] as $file) {
                    $file = Storage::disk('local')->put("/tmp", $file);
                    $value[] = [
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

    public function getRules(): array
    {
        if ($this->isMultiple()) {
            return [
                $this->getName() => in_array('required', $this->rules) ? ['required', 'array'] : ['array'],
                "{$this->getName()}.*" => $this->rules,
            ];
        }

        return [
            $this->getName() => $this->rules
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
            'rules' => $this->getRules(),
        ];
    }
}
