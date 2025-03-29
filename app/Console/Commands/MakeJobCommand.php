<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job {job} {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job for the domain';

    /**
     * The feature name without suffix
     *
     * @var string
     */
    private string $job;

    /**
     * The lucid service
     *
     * @var string
     */
    private string $domain;

    private string $namespaceTemplate = "App\\Domains\\%s\\Jobs";
    private string $directoryPathTemplate = "app/Domains/%s/Jobs";

    private string $existsErrorMessageTemplate = "Job '%s' already exists!";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        // Add 'Job' suffix if not present
        $this->appendJobSuffix();

        $path = $this->getPath();

        if (File::exists(base_path($path))) {
            $this->showError();

            return;
        }

        $stub = $this->getFilledStub();

        $this->writeToFile($path, $stub);

        $this->info('Job created successfully;');
    }

    private function extractAndInit(): void
    {
        $this->job = ucfirst($this->argument('job'));
        $this->domain = ucfirst($this->argument('domain'));
    }

    private function appendJobSuffix(): void
    {
        if (!str_ends_with($this->job, 'Job')) {
            $this->job .= 'Job';
        }
    }

    private function getPath(): string
    {
        return sprintf(
            $this->directoryPathTemplate,
            $this->domain
        ) . "/{$this->job}.php";
    }

    private function showError(): void
    {
        $this->error(
            sprintf($this->existsErrorMessageTemplate, $this->job)
        );
    }

    private function getFilledStub(): array|string
    {
        return str_replace(
          ['{{ class }}', '{{ namespace }}'],
          [$this->job, sprintf($this->namespaceTemplate, $this->domain)],
          File::get(base_path('stubs/job.stub'))
        );
    }

    private function writeToFile(
        string $path,
        array|string $stub
    ): void
    {
        File::ensureDirectoryExists(
            base_path(sprintf($this->directoryPathTemplate, $this->domain))
        );

        File::put(base_path($path), $stub);
    }
}
