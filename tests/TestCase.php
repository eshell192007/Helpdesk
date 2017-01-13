<?php

namespace Aviator\Helpdesk\Tests;

use Aviator\Database\Migrations\CreateHelpdeskTables;
use Aviator\Database\Migrations\CreateUsersTable;
use Aviator\Helpdesk\HelpdeskServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Notification;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../resources/factories');

        $this->setUpDatabase();

        $this->loadMigrationsFrom(__DIR__ . '/../resources/migrations');

        $this->createSupervisorUser();

        Notification::fake();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            HelpdeskServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', 'true');
        $app['config']->set('app.key', 'base64:2+SetJaztC7g0a1sSF81LYsDasiWymO6tp8yVv6KGrA=');
        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        if (isset($GLOBALS['altdb']) && $GLOBALS['altdb'] === true) {
            $this->setAlternateTablesInConfig($app);
        }
    }

    protected function setUpDatabase()
    {
        // Create testing database fixtures
        include_once __DIR__ . '/../database/migrations/2017_01_01_000000_create_users_table.php';
        (new CreateUsersTable())->up();
    }

    /**
     * Create the supervisor user. This is necessary as the supervisor user
     * is the fallback for notifications where an assignment or pool assignment
     * are not set.
     * @return void
     */
    protected function createSupervisorUser()
    {
        $userModel = config('helpdesk.userModel');

        $userModel::create([
            'name' => 'Super Visor',
            'email' => config('helpdesk.supervisor.email'),
        ]);
    }

    /**
     * Set alternate table names for testing that the database names
     * are properly variable everywhere
     * @param $app
     * @return void
     */
    protected function setAlternateTablesInConfig($app)
    {
        $prefix = 'hd_';

        $app->config->set('helpdesk.tables.users', 'users');
        $app->config->set('helpdesk.tables.tickets', $prefix . 'tickets');
        $app->config->set('helpdesk.tables.agents', $prefix . 'agents');
        $app->config->set('helpdesk.tables.agent_pool', $prefix . 'agent_pool');
        $app->config->set('helpdesk.tables.actions', $prefix . 'actions');
        $app->config->set('helpdesk.tables.generic_contents', $prefix . 'generic_contents');
        $app->config->set('helpdesk.tables.assignments', $prefix . 'assignments');
        $app->config->set('helpdesk.tables.due_dates', $prefix . 'due_dates');
        $app->config->set('helpdesk.tables.replies', $prefix . 'replies');
        $app->config->set('helpdesk.tables.pools', $prefix . 'pools');
        $app->config->set('helpdesk.tables.pool_assignments', $prefix . 'pool_assignments');
        $app->config->set('helpdesk.tables.closings', $prefix . 'closings');
        $app->config->set('helpdesk.tables.openings', $prefix . 'openings');
        $app->config->set('helpdesk.tables.notes', $prefix . 'notes');
    }
}