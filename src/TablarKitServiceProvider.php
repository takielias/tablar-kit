<?php

declare(strict_types=1);

namespace Takielias\TablarKit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\View\Compilers\BladeCompiler;
use Takielias\TablarKit\Commands\InstallTablarKit;
use Takielias\TablarKit\Commands\MakeTableCommand;
use Takielias\TablarKit\Rules\FilepondRule;

class TablarKitServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootRoutes();
        $this->bootMacro();
        $this->bootResources();
        $this->bootBladeComponents();
        $this->bootPublishing();

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'takielias');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'takielias');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->commands([
            InstallTablarKit::class,
            MakeTableCommand::class,
        ]);
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    private function bootResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tablar-kit');
    }


    private function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = config('tablar-kit.prefix', '');
            foreach (config('tablar-kit.components', []) as $alias => $component) {
                $blade->component($component, $alias, $prefix);
            }
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/tablar-kit.php', 'tablar-kit');

        // Register the service the package provides.
        $this->app->singleton('tablar-kit', function () {
            return new TablarKit;
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallTablarKit::class,
            ]);
        }
    }


    private function bootPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/tablar-kit.php' => $this->app->configPath('tablar-kit.php'),
            ], 'tablar-kit-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => $this->app->resourcePath('views/vendor/tablar-kit'),
            ], 'tablar-kit-views');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['tablar-kit'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/tablar-kit.php' => config_path('tablar-kit.php'),
        ], 'tablar-kit.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/takielias'),
        ], 'tablar-kit.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/takielias'),
        ], 'tablar-kit.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/takielias'),
        ], 'tablar-kit.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }

    private function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function bootMacro(): void
    {
        Rule::macro('filepond', function ($args) {
            return new FilepondRule($args);
        });
    }
}
