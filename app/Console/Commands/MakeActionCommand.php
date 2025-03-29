<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeActionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'action {action} {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new invokable controller connected to dedicated feature';

    /**
     * The action name
     *
     * @var string
     */
    private string $action;

    /**
     * The lucid service
     *
     * @var string
     */
    private string $service;

    private string $featureNamespaceTemplate = "App\\Services\\%s\\Features\\V1\\%s";

    private string $controllerNamespaceTemplate = "App\\Services\\%s\\Http\\Controllers\\V1";

    private string $controllerPathTemplate = "app/Services/%s/Http/Controllers/V1";

    /**
     * The feature namespace to use in controller stub
     *
     * @var string
     */
    private string $featureNamespace;

    /**
     * The feature class name to use in controller stub
     *
     * @var string
     */
    private string $feature;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        $this->handleFeature();

        $this->handleController();
    }

    private function extractAndInit(): void
    {
        $this->action = $this->argument('action');
        $this->service = $this->argument('service');
    }

    protected function handleFeature(): void
    {
        /**
         * The action name should not be suffixed with 'Feature'
         * or 'Controller'
         */
        $featureName = $this->toFeatureName();

        $this->call('feature', [
            'feature' => $featureName,
            'service' => $this->service
        ]);

        // fill feature and feature namespace
        $this->initFeatureNamespace($featureName);
        $this->initFeature($featureName);
    }

    protected function handleController(): void
    {
        $controllerName = $this->getControllerName();
        $path = $this->getControllerPath($controllerName);

        // Check if file already exists
        if (File::exists(base_path($path))) {
            $this->error("Controller {$controllerName} already exists!");

            return;
        }

        $stub = $this->getControllerFilledStub($controllerName);

        $this->writeControllerFile($path, $stub);

        $this->info("Invokable controller and respective feature created successfully.");
    }

    /**
     * ---------------------------------------------------
     * -------------------- Utilities --------------------
     * ---------------------------------------------------
     */

    private function toFeatureName(): string
    {
        return ucfirst($this->action) . 'Feature';
    }

    private function initFeatureNamespace(string $featureName): void
    {
        $this->featureNamespace = sprintf(
            $this->featureNamespaceTemplate,
            $this->service,
            $featureName
        );
    }

    private function initFeature(string $featureName): void
    {
        $this->feature = $featureName;
    }

    protected function getControllerName(): string
    {
        return ucfirst($this->action) . 'Controller';
    }

    /**
     * get controller relative path
     *
     * @param string $controllerName
     * @return string
     */
    protected function getControllerPath(string $controllerName): string
    {
        return sprintf(
                $this->controllerPathTemplate,
                $this->service
            ) . "/{$controllerName}.php";
    }

    private function getControllerFilledStub(
        string $controllerName
    ): array|string
    {
        return str_replace(
            [
                "{{ class }}",

                // namespaces
                "{{ namespace }}",
                '{{ featureNamespace }}',

                '{{ feature }}' // feature class
            ],
            [
                $controllerName,

                // namespaces
                sprintf($this->controllerNamespaceTemplate, $this->service),
                $this->featureNamespace,

                $this->toFeatureName() // feature
            ],
            File::get(base_path('stubs/invokable_controller.stub'))
        );
    }

    private function writeControllerFile(
        string $path,
        array|string $stub
    ): void
    {
        File::ensureDirectoryExists(base_path(
            sprintf($this->controllerPathTemplate, $this->service)
        ));

        File::put(base_path($path), $stub);
    }
}
