<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class WorkerDeals extends Migration
{
    public function up()
    {
        $this->schema->create('worker_deals',function (Blueprint $table){
            $table->increments('id');
            $table->integer('order_id');
            $table->string('name','127');
            $table->string('dead_name','127');
            $table->string('agent_name','127');
            $table->string('tag','127');
            $table->string('funeral','127');
            $table->integer('status_id');
            $table->integer('task_done');
            $table->integer('tasks_totals');
            $table->decimal('money_to_pay', 10, 2);
            $table->decimal('total_money', 10,2);
            $table->integer('brigade_id');
            $table->dateTime('date_create');
        });
    }

    public function down()
    {
        $this->schema->drop('worker_deals');
    }
}
