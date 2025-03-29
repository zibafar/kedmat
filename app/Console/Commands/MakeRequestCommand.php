<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request {className} {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request in the service';

    /**
     * @var string
     */
    private string $className;

    /**
     * The lucid service
     *
     * @var string
     */
    private string $service;

    private string $stubPath = 'stubs/request.stub';

    private string $directoryPathTemplate = "app/Services/%s/Http/Requests";

    private string $namespaceTemplate = "App\\Services\\%s\\Http\\Requests";

    private string $suffix = 'Request';


    private string $existsErrorMessageTemplate = "Form request '%s' already exists!";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        // Add 'Controller' suffix if not present
        $this->appendSuffix();

        $path = $this->getPath();

        // Check if file already exists
        if (File::exists(base_path($path))) {
            $this->showError();

            return;
        }

        $stub = $this->getFilledStub();

        $this->ensurePath();

        $this->make($path, $stub);

        $this->info('Request created successfully');
    }

    /**
     * @return void
     */
    private function extractAndInit(): void
    {
        $this->className = ucfirst($this->argument('className'));
        $this->service = ucfirst($this->argument('service'));
    }

    private function getPath(): string
    {
        return sprintf(
                $this->directoryPathTemplate,
                $this->service
            ) . "/{$this->className}.php";
    }

    private function ensurePath(): void
    {
        File::ensureDirectoryExists(base_path(sprintf(
            $this->directoryPathTemplate,
            $this->service
        )));
    }

    private function make(
        string       $path,
        string|array $stub
    ): void
    {
        File::put(base_path($path), $stub);
    }

    private function getFilledStub(): array|string
    {
        return str_replace(
            [
                '{{ namespace }}',
                '{{ class }}'
            ],
            [
                sprintf(
                    $this->namespaceTemplate,
                    $this->service,
                ),
                $this->className
            ],
            File::get(base_path($this->stubPath))
        );
    }

    private function appendSuffix(): void
    {
        if (!str_ends_with($this->className, $this->suffix)) {
            $this->className .= $this->suffix;
        }
    }

    private function showError(): void
    {
        $this->error(sprintf($this->existsErrorMessageTemplate, $this->className));
    }
}
