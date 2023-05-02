<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class Orders extends Migration
{
    public function up()
    {
        $this->schema->create('orders',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->dateTime('date_create');
        });
    }

    public function down()
    {
        $this->schema->drop('orders');
    }
}
