<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

abstract class AbstractViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Shared Namespace for the Composers
     *
     * @var string
     */
    protected $namespace;

    /**
     * List Composers to use
     *
     * @see https://laravel.com/docs/5.3/views#view-composers
     * @var array
     */
    protected $composers = [];

    /**
     * List singletons to register
     *
     * @var array
     */
    public $singletons = [];

    /**
     * List of additional Service Providers to Register
     *
     * @var Array
     */
    protected $providers = [];

    /**
     * Boot Singletons
     *
     * @return Void
     */
    public function boot()
    {
        # Bind the Singletons, as necessary
        $this->registerSingletons();

        # do composers
        $this->app->view->composers( $this->getComposers() );
    }

    /**
     * Register Composers
     *
     * @return Void
     */
    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Register Composers that need to run only once
     *
     * @return void
     */
    protected function registerSingletons()
    {
        # Make the singleton once, and then bind the instance.
        foreach($this->prependNamespace($this->singletons) as $singleton){
            $this->app->instance($singleton, $this->app->make($singleton));
        }
    }

    /**
     * Return composers
     *
     * @return Array
     */
    protected function getComposers()
    {
        # split keys from values
        list($composers, $views)   = array_divide($this->composers);
        # apply callback to prepend Namespace
        return array_combine($this->prependNamespace($composers), $views);
    }

    /**
     * Prepend the shared namespace
     *
     * @param  array $composers
     * @return array
     */
    protected function prependNamespace($composers)
    {
        return array_map(function($key){
            return empty($this->namespace) ? $key : "{$this->namespace}\\$key";
        }, $composers);
    }
}
