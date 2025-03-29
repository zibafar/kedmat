<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature {feature} {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new feature in the service';

    /**
     * The feature name
     *
     * @var string
     */
    private string $feature;

    /**
     * The lucid service
     *
     * @var string
     */
    private string $service;

    private string $namespaceTemplate = "App\\Services\\%s\\Features\\V1";
    private string $directoryPathTemplate = "app/Services/%s/Features/V1";

    private string $existsErrorMessageTemplate = "Feature '%s' already exists!";


    /**
     * Execute the create feature command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        // Add 'Feature' suffix if not present
        $this->appendFeatureSuffix();

        $path = $this->getPath();

        // Check if file already exists
        if (File::exists(base_path($path))) {
            $this->showError();

            return;
        }

        $stub = $this->getFilledStub();

        $this->ensurePath();

        $this->make($path, $stub);

        $this->info('Feature created successfully.');
    }

    /**
     * @return void
     */
    private function appendFeatureSuffix(): void
    {
        if (!str_ends_with($this->feature, 'Feature')) {
            $this->feature .= 'Feature';
        }
    }

    /**
     * @return array|string|string[]
     */
    private function getFilledStub(): string|array
    {
        return str_replace(
            ['{{ class }}', '{{ namespace }}'],
            [$this->feature, sprintf($this->namespaceTemplate, $this->service)],
            File::get(base_path('stubs/feature.stub'))
        );
    }

    /**
     * @param string $path
     * @param array|string $stub
     * @return void
     */
    private function make(
        string       $path,
        array|string $stub
    ): void
    {
        File::put(base_path($path), $stub);
    }

    /**
     * @return void
     */
    private function extractAndInit(): void
    {
        $this->feature = $this->argument('feature');
        $this->service = $this->argument('service');
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return sprintf(
                $this->directoryPathTemplate,
                $this->service
            ) . "/{$this->feature}.php";
    }

    /**
     * @return void
     */
    private function showError(): void
    {
        $this->error(sprintf($this->existsErrorMessageTemplate, $this->feature));
    }

    private function ensurePath(): void
    {
        File::ensureDirectoryExists(base_path(sprintf(
            $this->directoryPathTemplate,
            $this->service
        )));
    }
}
