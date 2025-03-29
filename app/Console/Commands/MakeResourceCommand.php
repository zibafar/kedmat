<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource {resource} {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a resource controller with features in the service';

    private string $featureNamespaceTemplate = "App\\Services\\%s\\Features\\V1\\%s";
    private string $controllerNamespaceTemplate = "App\\Services\\%s\\Http\\Controllers\\V1";
    private string $controllerPathTemplate = "app/Services/%s/Http/Controllers/V1";
    private array $methods = [
        'index',
        'store',
        'show',
        'update',
        'destroy'
    ];

    private array $featureNameSpaces = [
        'index' => null,
        'store' => null,
        'show' => null,
        'update' => null,
        'destroy' => null
    ];

    private array $features = [
        'index' => null,
        'store' => null,
        'show' => null,
        'update' => null,
        'destroy' => null
    ];

    /**
     * The resource
     *
     * @var string
     */
    protected string $resource;

    /**
     * The service name
     *
     * @var string
     */
    protected string $service;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->extractAndInit();

        $this->handleFeatures();

        $this->handleController();
    }

    /**
     * @return void
     */
    private function extractAndInit(): void
    {
        $this->resource = $this->argument('resource');
        $this->service = $this->argument('service');
    }

    /**
     * Writes feature files
     *
     * @return void
     */
    protected function handleFeatures(): void
    {
        foreach ($this->methods as $method) {
            $featureName = $this->toFeatureName($method);

            $this->call('feature', [
                'feature' => $featureName,
                'service' => $this->service,
            ]);

            // fill feature & feature namespace
            $this->initFeatureNamespace($method, $featureName);
            $this->initFeature($method, $featureName);
        }
    }

    /**
     * Writes controller file
     *
     * @return void
     */
    protected function handleController(): void
    {
        $controllerName = $this->getControllerName();
        $path = $this->getControllerPath($controllerName);

        // Check if file already exists
        if (File::exists(base_path($path))) {
            $this->error("Controller '{$controllerName}' already exists!");

            return;
        }

        $stub = $this->getControllerFilledStub($controllerName);

        $this->writeControllerFile($path, $stub);

        $this->info("Resource controller and features created successfully.");
    }

    /**
     * ---------------------------------------------------
     * -------------------- Utilities --------------------
     * ---------------------------------------------------
     */

    /**
     * Map method names to feature names
     *
     * @param string $method
     * @return string
     */
    protected function toFeatureName(
        string $method
    ): string
    {
        return match ($method) {
            'index' => 'Get' . ucfirst(Str::plural($this->resource)) . 'Feature',
            'show' => 'Show' . ucfirst($this->resource) . 'Feature',
            'store' => 'Create' . ucfirst($this->resource) . 'Feature',
            'update' => ucfirst($method) . ucfirst($this->resource) . 'Feature',
            'destroy' => 'Delete' . ucfirst($this->resource) . 'Feature',
        };
    }

    /**
     * @param string $method
     * @param string $featureName
     * @return void
     */
    protected function initFeatureNamespace(
        string $method,
        string $featureName,
    ): void
    {
        $this->featureNameSpaces[$method] = sprintf(
            $this->featureNamespaceTemplate,
            $this->service,
            $featureName
        );
    }

    protected function getControllerName(): string
    {
        return ucfirst($this->resource) . 'Controller';
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

    /**
     * @param string $controllerName
     * @return array|string
     */
    protected function getControllerFilledStub(
        string $controllerName
    ): array|string
    {
        return str_replace(
            [
                '{{ class }}',

                // namespaces
                '{{ namespace }}',
                '{{ showFeatureNamespace }}',
                '{{ getFeatureNamespace }}',
                '{{ createFeatureNamespace }}',
                '{{ updateFeatureNamespace }}',
                '{{ deleteFeatureNamespace }}',

                // feature classes
                '{{ show }}',
                '{{ get }}',
                '{{ create }}',
                '{{ update }}',
                '{{ delete }}'
            ],
            [
                $controllerName,

                // namespaces
                sprintf($this->controllerNamespaceTemplate, $this->service),
                $this->featureNameSpaces['show'],
                $this->featureNameSpaces['index'],
                $this->featureNameSpaces['store'],
                $this->featureNameSpaces['update'],
                $this->featureNameSpaces['destroy'],

                // feature classes
                $this->features['show'],
                $this->features['index'],
                $this->features['store'],
                $this->features['update'],
                $this->features['destroy'],
            ],
            File::get(base_path('stubs/resource_controller.stub'))
        );
    }

    /**
     * write controller file to filesystem
     *
     * @param string $path
     * @param array|string $stub
     * @return void
     */
    private function writeControllerFile(
        string       $path,
        array|string $stub
    ): void
    {
        File::ensureDirectoryExists(base_path(
            sprintf($this->controllerPathTemplate, $this->service)
        ));

        File::put(base_path($path), $stub);
    }

    private function initFeature(mixed $method, string $featureName): void
    {
        $this->features[$method] = $featureName;
    }
}
