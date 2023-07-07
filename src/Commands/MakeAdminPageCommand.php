<?php

namespace Bengr\Admin\Commands;

use Bengr\Support\Commands\Concerns\CanValidateInput;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

// DOTO: 

class MakeAdminPageCommand extends Command
{
    use Concerns\HasStub;
    use Concerns\HasTemplates;
    use CanValidateInput;

    protected $signature = 'admin:page {name : Name of the page} {--c|--complex : Create more complex page by providing more params}';

    protected $description = 'Creates page component for administration';

    protected string $stub = 'page.stub';

    public function handle()
    {
        try {
            if (File::exists($this->getPath())) {
                $this->components->error('Page already exists.');

                return static::FAILURE;
            }

            $this->getStub($this->stub)
                ->replaceStub('class', $this->getClassName())
                ->replaceStub('namespace', $this->getNamespace())
                ->replaceStub('title', $this->getTitle())
                ->replaceStub('description', $this->getDesc())
                ->replaceStub('slug', $this->getSlug())
                ->replaceStub('middlewares', $this->getMiddlewares())
                ->setTemplate($this->option('complex') ? $this->choice('Choose template for page', $this->getTemplates(), $this->getDefaultTemplate()) : $this->getDefaultTemplate())
                ->saveStub();



            $this->components->info(sprintf('%s [%s] created successfully.', 'Page', $this->getPath()));

            return static::SUCCESS;
        } catch (\Throwable $e) {
            dd($e);
            $this->components->error('Something went wrong.');

            return static::FAILURE;
        }
    }

    protected function getNameArgument(): string
    {
        return str_replace('\\', '/', $this->argument('name'));
    }

    protected function getDirectoryPaths(): array
    {
        $paths = [];
        $prev_path = config('admin.components.pages.path');

        foreach (array_slice(explode('/', $this->getNameArgument()), 0, -1) as $directory) {
            $prev_path = $prev_path . '/' . $directory;
            $paths[] = $prev_path;
        }

        return $paths;
    }

    protected function getClassName(): string
    {
        $separated = explode('/', $this->getNameArgument());

        return $separated[count($separated) - 1];
    }

    protected function getNamespace(): string
    {
        $namespace = config('admin.components.pages.namespace') . '\\' . implode('\\', array_slice(explode('/', $this->getNameArgument()), 0, -1));

        return rtrim($namespace, '\\');
    }

    protected function getTitle(): string
    {
        $title = $this->option('complex') ? $this->ask('Title', $this->getClassName()) : $this->getClassName();

        return '"' . $title . '"';
    }

    protected function getDesc(): string
    {
        $description = $this->option('complex') ? $this->ask('Description', $this->getClassName()) : $this->getClassName();

        return '"' . $description . '"';
    }

    protected function getSlug(): string
    {
        $slug = $this->option('complex') ? strtolower($this->ask('Slug', strtolower($this->getNameArgument()))) : strtolower($this->getNameArgument());

        return '"' . $slug . '"';
    }

    protected function getMiddlewares(): string
    {
        $middlewares = [];

        if ($this->option('complex') && $this->confirm('For authenticated admins?', true)) {
            $middlewares[] = 'auth:' . config('admin.auth.guard');
        }

        if (!$this->option('complex')) {
            $middlewares[] = 'auth:' . config('admin.auth.guard');
        }


        return '["' . implode('", "', $middlewares) . '"]';
    }

    protected function getPath(): string
    {
        return config('admin.components.pages.path') . '/' . $this->getNameArgument() . '.php';
    }
}
