<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFactoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factory {factory} {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new factory in the service';

    /**
     * The factory name
     *
     * @var string
     */
    private string $factory;

    /**
     * The model name
     *
     * @var string
     */
    private string $model;
    /**
     * The lucid service
     *
     * @var string
     */
    private string $service;

    private string $namespaceTemplate = "App\\Services\\%s\\Database\\Factories";
    private string $directoryPathTemplate = "app/Services/%s/Database/Factories";

    private string $existsErrorMessageTemplate = "factory '%s' already exists!";


    /**
     * Execute the create factory command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        // Add 'factory' suffix if not present
        $this->appendFactorySuffix();
        $this->removeFactorySuffix();

        $path = $this->getPath();

        // Check if file already exists
        if (File::exists(base_path($path))) {
            $this->showError();

            return;
        }

        $stub = $this->getFilledStub();

        $this->ensurePath();

        $this->make($path, $stub);

        $this->info('Factory created successfully
        : '. $path);
    }

    /**
     * @return void
     */
    private function appendFactorySuffix(): void
    {
        if (!str_ends_with($this->factory, 'Factory')) {
            $this->factory .= 'Factory';
        }
    }

    /**
     * @return void
     */
    private function removeFactorySuffix(): void
    {
        if (str_ends_with($this->model, 'Factory')) {
            $this->model = substr($this->model, 0, - strlen('Factory'));
        }
    }

    /**
     * @return array|string|string[]
     */
    private function getFilledStub(): string|array
    {
        return str_replace(
            ['{{ class }}', '{{ namespace }}' , '{{ model }}'],
            [$this->factory, sprintf($this->namespaceTemplate, $this->service), $this->model],
            File::get(base_path('stubs/factory.stub'))
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
        $this->factory = $this->argument('factory');
        $this->model = $this->argument('factory');
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
            ) . "/{$this->factory}.php";
    }

    /**
     * @return void
     */
    private function showError(): void
    {
        $this->error(sprintf($this->existsErrorMessageTemplate, $this->factory));
    }

    private function ensurePath(): void
    {
        File::ensureDirectoryExists(base_path(sprintf(
            $this->directoryPathTemplate,
            $this->service
        )));
    }
}
