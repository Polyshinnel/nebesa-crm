<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;


final class OrderDetails extends Migration
{
    public function up()
    {
        $exists = $this->hasTable('order_details');

        if(!$exists) {
            $this->schema->create('order_details',function (Blueprint $table){
                $table->increments('id');
                $table->integer('order_id');
                $table->string('name','127');
                $table->integer('position');
                $table->decimal('quantity', 10, 2);
                $table->decimal('price', 10, 2);
            });
        }

    }

    public function down()
    {
        $this->schema->drop('order_details');
    }
}
