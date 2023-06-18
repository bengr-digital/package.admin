<?php

namespace Bengr\Admin\Commands\Concerns;

use Illuminate\Support\Facades\File;

trait HasStub
{
    private string $STUB_PATH = __DIR__ . '/../stubs';

    private string $stubContent = '';

    protected function getStub(?string $stub): self
    {
        $this->stubContent = File::get($this->STUB_PATH . '/' . $stub);

        return $this;
    }

    protected function replaceStub(string $search, string $content): self
    {
        $this->stubContent = str_replace(['{{ ' . $search . ' }}', '{{' . $search . '}}'], $content, $this->stubContent);

        return $this;
    }

    protected function getStubContent(): string
    {
        return $this->stubContent;
    }

    protected function saveStub(): void
    {
        $directories = $this->getDirectoryPaths();

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        File::put($this->getPath(), $this->stubContent);
    }
}
