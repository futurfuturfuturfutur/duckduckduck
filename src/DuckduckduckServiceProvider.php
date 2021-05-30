<?php

namespace Futurfuturfuturfutur\Duckduckduck;

use Futurfuturfuturfutur\Duckduckduck\Http\Middleware\DuckduckduckMiddleware;
use Futurfuturfuturfutur\Duckduckduck\Commands\GenerateCommand;
use Futurfuturfuturfutur\Duckduckduck\Commands\InitCommand;
use Futurfuturfuturfutur\Duckduckduck\Services\Format\FormatServiceInterface;
use Futurfuturfuturfutur\Duckduckduck\Services\Format\SwaggerFormatService;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class DuckduckduckServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->registerCommands();
            $this->registerBindings();
        }

        $this->registerMiddlewares();
    }

    private function publishConfig()
    {
        $this->publishes([__DIR__ . '/../config/duckduckduck.php' => config_path('duckduckduck.php')], 'config');
        $this->publishes([__DIR__ . '/../duckduckduck' => base_path('duckduckduck')], 'package-dir');
    }

    private function registerCommands()
    {
        $this->commands([
            InitCommand::class,
            GenerateCommand::class,
        ]);
    }

    private function registerBindings()
    {
        $this->app->bind(FormatServiceInterface::class, function (){
            switch (Config::get('duckduckduck.type')){
                default:
                    return new SwaggerFormatService();
            }
        });
    }

    private function registerMiddlewares()
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(DuckduckduckMiddleware::class);
    }
}
