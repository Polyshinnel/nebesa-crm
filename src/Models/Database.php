<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public function __construct($db)
    {
        $capsule = new Capsule();

        $capsule->addConnection([
            'driver' => $db['db_driver'],
            'host' => $db['db_host'],
            'database' => $db['db_name'],
            'username' => $db['db_user'],
            'password' => $db['db_pass'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }
}