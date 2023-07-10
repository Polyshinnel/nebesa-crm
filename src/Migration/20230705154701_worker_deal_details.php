<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class WorkerDealDetails extends Migration
{
    public function up()
    {
        $this->schema->create('worker_deal_details',function (Blueprint $table){
            $table->increments('id');
            $table->integer('worker_deal');
            $table->string('product_name', '256');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('state');
        });
    }

    public function down()
    {
        $this->schema->drop('worker_deal_details');
    }
}
