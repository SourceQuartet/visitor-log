<?php namespace SourceQuartet\VisitorLog;

use Illuminate\Support\ServiceProvider;
use SourceQuartet\VisitorLog\Repositories\Visitor\VisitorManager;

class VisitorLogServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadViewsFrom(__DIR__.'../../views/', 'visitor-log');

		$this->publishes([
			__DIR__.'../../views/' => base_path('resources/views/vendor/visitor-log'),
		]);

        $this->publishes([
            __DIR__.'../../config/visitor-log.php' => config_path('visitor-log.php'),
        ]);

        $this->publishes([
            __DIR__.'../../migrations/' => database_path('migrations')
        ], 'migrations');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'../../config/visitor-log.php', 'visitor-log'
        );

        $this->app->bind('SourceQuartet\VisitorLog\Repositories\Visitor\VisitorManager', 'SourceQuartet\VisitorLog\Repositories\Visitor\Visitor');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('visitor');
	}

}