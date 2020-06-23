<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */

namespace Tmdb\Laravel;

use Illuminate\Support\ServiceProvider;
use Tmdb\ApiToken;
use Tmdb\Client;

class TmdbServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            'tmdb'
        );

        $this->app->bind(
            'Tmdb\Laravel\Adapters\EventDispatcherAdapter',
            'Tmdb\Laravel\Adapters\EventDispatcher'
        );

        // Let the IoC container be able to make a Symfony event dispatcher
        $this->app->bind(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface',
            'Symfony\Component\EventDispatcher\EventDispatcher'
        );

        // Setup default configurations for the Tmdb Client
        $this->app->singleton('Tmdb\Client', function () {
            $options = config('tmdb.options');

            // Use an Event Dispatcher that uses the Laravel event dispatcher
            $options['event_dispatcher'] = $this->app->make('Tmdb\Laravel\Adapters\EventDispatcherAdapter');

            // Register the client using the key and options from config
            $token = new ApiToken(config('tmdb.api_key'));
            return new Client($token, $options);
        });

        // bind the configuration (used by the image helper)
        $this->app->bind('Tmdb\Model\Configuration', function () {
            $configuration = $this->app->make('Tmdb\Repository\ConfigurationRepository');
            return $configuration->load();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('tmdb.php'),
            ], 'tmdb-config');
        }
    }
}
