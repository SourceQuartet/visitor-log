<?php
use Orchestra\Testbench\TestCase;

/**
 * Class TestCaseDB
 */
abstract class TestCaseDB extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['SourceQuartet\VisitorLog\VisitorLogServiceProvider'];
    }
    /**
     * Setup the DB before each test.
     */
    public function setUp()
    {
        parent::setUp();
        // This should only do work for Sqlite DBs in memory.
        $artisan = $this->app->make('Illuminate\Contracts\Console\Kernel');
        app('db')->beginTransaction();
        $this->migrate($artisan);
        $this->migrate($artisan, '/../../../../tests/migrations');
        // Set up the User Test Model
        app('config')->set('visitor-log.model', 'SourceQuartet\VisitorLog\VisitorModel');
        app('config')->set('visitor-log.user.model', 'SourceQuartet\Tests\Models\User');
    }
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ));
    }
    /**
     * Rollback transactions after each test.
     */
    public function tearDown()
    {
        app('db')->rollback();
    }
    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }
    /**
     * Migrate the migrations files
     *
     * @param        $artisan
     * @param string $path
     */
    private function migrate($artisan, $path = '/../../../../src/migrations')
    {
        $artisan->call('migrate', [
            '--database' => 'testbench',
            '--path'     => $path
        ]);
    }
}
