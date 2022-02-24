<?php

namespace TargetConvert\Material;

use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TargetConvertMaterialServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Console\Commands\MaterialInstall::class,
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'materials');

        $this->registerPublishing();

        $this->compatibleBlade();
    }

    /**
     * Remove default feature of double encoding enable in laravel 5.6 or later.
     *
     * @return void
     */
    protected function compatibleBlade()
    {
        $reflectionClass = new \ReflectionClass('\Illuminate\View\Compilers\BladeCompiler');

        if ($reflectionClass->hasMethod('withoutDoubleEncoding')) {
            Blade::withoutDoubleEncoding();
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'laravel-material-config');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'laravel-material-lang');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'laravel-material-migrations');
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/target-convert/material')], 'laravel-material-assets');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('snowflake', function () {
            return (new Snowflake())
                ->setStartTimeStamp(strtotime('2022-02-14')*1000) // 開始在轉客來上班的日期
                ->setSequenceResolver(new LaravelSequenceResolver(
                    $this->app->get('cache')->store()
                ));
        });

        $this->commands($this->commands);
    }
}
