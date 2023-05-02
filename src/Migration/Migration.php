<?php


namespace App\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    public $capsule;
    public $schema;

    public function init()
    {
        $container = require __DIR__.'/../../bootstrap/container.php';
        $dbSettings = $container->get('config')['db_settings'];

        $this->capsule = new Capsule();
        $this->capsule->addConnection([
            'driver' => $dbSettings['db_driver'],
            'host' => $dbSettings['db_host'],
            'database' => $dbSettings['db_name'],
            'username' => $dbSettings['db_user'],
            'password' => $dbSettings['db_pass'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}