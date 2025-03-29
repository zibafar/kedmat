<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeUnitTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unittest {name} {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test in the service';

    /**
     * @var string
     */
    private string $className;

    /**
     * The service name
     */
    private string $service;
//Tests\Feature\Services\Auth\Http\Controllers\V1\User;
    private string $directoryPathTemplate = 'tests/Feature/Services/%s/Http/Controllers/V1';

    private string $stubPath = 'stubs/test.plain.stub';
    private string $namespaceTemplate = 'Tests\\Feature\\Services\\%s\\Http\\Controllers\\V1\\%s';
    private string $existsErrorMessageTemplate = "Test '%s' already exists!";

    private string $suffix = 'ControllerTest';

    private string $infoMessage = 'Test created successfully';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        // Add suffix if not present
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

        $this->info($path." : ".$this->infoMessage);
    }

    /**
     * @return void
     */
    private function extractAndInit(): void
    {
        $this->className = ucfirst($this->argument('name'));
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
                    $this->className
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
