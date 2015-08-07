<?php namespace SourceQuartet\VisitorLog;

use Illuminate\Support\ServiceProvider;
use SourceQuartet\VisitorLog\Visitor\VisitorRepository;

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
		$this->loadViewsFrom(dirname(dirname(__DIR__)).'/views/', 'visitor-log');

		$this->publishes([
			dirname(dirname(__DIR__)).'/views/' => base_path('resources/views/vendor/visitor-log'),
		]);

        $this->publishes([
			dirname(dirname(__DIR__)).'/config/visitor-log.php' => config_path('visitor-log.php'),
        ]);

        $this->publishes([
			dirname(dirname(__DIR__)).'/migrations/' => database_path('migrations')
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
			dirname(dirname(__DIR__)).'/config/visitor-log.php', 'visitor-log'
        );

        $this->app->bind('SourceQuartet\VisitorLog\Visitor\Visitor', 'SourceQuartet\VisitorLog\Visitor\VisitorManager');

        $this->app->bind('visitor.repository', function()
        {
            return new VisitorRepository(new VisitorModel());
        });

        $this->app->bind('visitor', function()
        {
            return new Visitor($this->app['visitor.repository'], $this->app['session.store'], $this->app['config']);
        });
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