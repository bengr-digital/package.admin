<?php

namespace Bengr\Admin\Commands;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Support\Commands\Concerns\CanValidateInput;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Terminal;
use Illuminate\Support\Str;

class AdminPageListCommand extends Command
{
    use CanValidateInput;

    protected $signature = 'admin:list';

    protected $description = 'List all pages in administration';

    public function handle()
    {
        $terminalWidth = (new Terminal)->getWidth();
        $table = new Table($this->output);

        $table->setHeaders(['Page', 'Title', 'Slug', 'Auth']);
        $table->setColumnWidths([($terminalWidth / 100) * 20, ($terminalWidth / 100) * 30, ($terminalWidth / 100) * 20, ($terminalWidth / 100) * 3]);

        foreach (BengrAdmin::getPages() as $page) {
            $isAuth = collect(app($page)->getMiddlewares())->contains(fn ($middleware) => Str::of($middleware)->startsWith('auth:'));
            $table->addRow(['<fg=white;options=bold>' . $page . '</>', app($page)->getTitle(), '<fg=gray>' . '/' . app($page)->getSlug() . '</>', $isAuth ? '<fg=green;options=bold>true</>' : '<fg=red;options=bold>false</>']);
        }

        $table->render();
    }
}
