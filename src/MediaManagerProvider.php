<?php

namespace Marcoboom\MediaManager;

use Illuminate\Support\ServiceProvider;

class MediaManagerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		if (! $this->app->routesAreCached()) {
			require __DIR__.'/routes.php';
		}
		
		$this->loadMigrationsFrom(__DIR__.'/../resources/migrations');

		$this->loadViewsFrom(__DIR__.'/../resources/views', 'media-manager');

		$this->publishes([
        	__DIR__.'/../resources/config/media.php' => config_path('media.php'),
    	], 'config');

		$this->publishes([
	   		__DIR__.'/../resources/views' => resource_path('views/vendor/media-manager'),
   		], 'views');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
		$this->mergeConfigFrom(
			__DIR__.'/../resources/config/media.php', 'media'
		);
    }
}
