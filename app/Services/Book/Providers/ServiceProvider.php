<?php

namespace App\Services\Book\Providers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register Sample service stuff.
     *
     * @return void
     */
    public function register(): void
    {
        /**
         * TODO: uncomment if you want to make use of this Sample service
         */
         $this->app->register(RouteServiceProvider::class);
         $this->registerResources();
    }

    public function boot()
    {
    }

    /**
     * Register the Sample service resource namespaces.
     *
     * @return void
     */
    protected function registerResources(): void
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('Book', realpath(__DIR__ . '/../Resources/Lang'));

        View::addNamespace('Book', base_path('resources/views/vendor/Book'));
        View::addNamespace('Book', realpath(__DIR__ . '/../Resources/views'));
    }
}
