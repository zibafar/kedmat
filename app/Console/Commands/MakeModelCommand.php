<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model {model} {subdirectory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model';

    /**
     * The model (with possible directories)
     */
    private string $model;

    /**
     * The default path to models
     */
    private string $path = 'app/Data/Models';

    /**
     * @var string
     */
    private string $relativeNamespace = 'Data/Models';

    /**
     * Optional subdirectory
     *
     * @var string|null
     */
    private ?string $subdirectory;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        $this->initPathAndNamespace();

        // Ensure the director exists
        $this->ensurePath();

        $this->make();

        $this->info('The model created successfully.');
    }

    private function extractAndInit(): void
    {
        $this->model = ucfirst($this->argument('model'));
        $this->subdirectory = ucfirst($this->argument('subdirectory'));
    }

    private function ensurePath(): void
    {
        File::ensureDirectoryExists(base_path($this->path));
    }

    private function make(): void
    {
        $this->call('make:model', [
            'name' => $this->relativeNamespace
        ]);
    }

    private function initPathAndNamespace(): void
    {
        if ($this->subdirectory) {
            $this->path .= "/{$this->subdirectory}";
            $this->relativeNamespace .= "/{$this->subdirectory}";
        }
        $this->relativeNamespace .= "/{$this->model}";
    }
}
