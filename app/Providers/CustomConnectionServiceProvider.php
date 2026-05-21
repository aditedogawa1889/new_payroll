<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Database\CustomSqlServerConnection;
use Illuminate\Support\Facades\DB;

class CustomConnectionServiceProvider extends ServiceProvider
{
    public function register()
    {
        DB::extend('sqlsrv', function ($config, $name) {
            $config['name'] = $name;
            
            $hostSqlsrv = $config['host'];
            $databaseSqlsrv = $config['database'];
            $usernameSqlsrv = $config['username'];
            $passwordSqlsrv = $config['password'];

            $pdoSqlsrv = new \PDO("sqlsrv:Server=$hostSqlsrv;Database=$databaseSqlsrv", $usernameSqlsrv, $passwordSqlsrv);

            return new CustomSqlServerConnection(
                $pdoSqlsrv, 
                $config['database'], 
                $config['prefix'] ?? '', 
                $config
            );
        });
    }
}
