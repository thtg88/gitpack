<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DropDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:drop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop selected database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws RuntimeException If database connection not mysql nor pgsql, and if database name not configured (null or "forge").
     */
    public function handle()
    {
        if (! $this->confirm(
            'This is going to delete all your information. ARE YOU SURE?'
        )) {
            $this->info('Exiting.');
            return;
        }

        // Get default database connection from config
        $default_database_connection = config('database.default');

        // Get database name from default database connection
        $database_name = config(
            'database.connections.'.$default_database_connection.'.database'
        );

        if ($database_name === null || $database_name === 'forge') {
            throw new \RuntimeException(
                'Database not configured for: '.
                $default_database_connection
            );
        }

        if ($default_database_connection === 'pgsql') {
            $this->info('Dropping PostgreSQL database '.$database_name.' ...');

            $this->dropPostgreSql($database_name);

            $this->info('PostgreSQL database '.$database_name.' dropped!');

            return;
        }

        if ($default_database_connection === 'mysql') {
            $this->info('Dropping MySQL database '.$database_name.' ...');

            $this->dropMySql($database_name);

            $this->info('MySQL database '.$database_name.' dropped!');

            return;
        }

        throw new \RuntimeException(
            'Database type not supported: '.
            $default_database_connection
        );

        \DB::statement('CREATE DATABASE '.$database_name.';');
    }

    /**
     * Drop the PostgreSQL database from a given database name.
     *
     * @param string $database_name
     * @return mixed
     * @throws InvalidArgumentException If database does not exists
     */
    protected function dropPostgreSql($database_name)
    {
        // We reset default database name to "postgres"
        // as we can not delete the database we are connected to
        config(['database.connections.pgsql.database' => 'postgres']);

        // Get databases names
        $databases = array_map(
            static function ($database) {
                return $database->datname;
            },
            \DB::select('SELECT datname FROM pg_database')
        );

        if (array_search($database_name, $databases) !== false) {
            \DB::statement('DROP DATABASE '.$database_name.';');
            return;
        }

        throw new \InvalidArgumentException(
            'Database does not exist: '.
            $database_name
        );
    }

    /**
     * Drop a MySQL database from a given database name.
     *
     * @param string $database_name
     * @return mixed
     * @throws InvalidArgumentException If database does not exists
     */
    protected function dropMySql($database_name)
    {
        // Get databases names
        $databases = array_map(
            static function ($database) {
                return $database->Database;
            },
            \DB::select('SHOW DATABASES;')
        );

        if (array_search($database_name, $databases) !== false) {
            \DB::statement('DROP DATABASE '.$database_name.';');
            return;
        }

        throw new \InvalidArgumentException(
            'Database does not exist: '.
            $database_name
        );
    }
}
