<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;


final class Deals extends Migration
{
    public function up()
    {
        $this->schema->create('deals',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('agent','127');
            $table->string('tag','127');
            $table->string('dead_name','127');
            $table->string('customer_name','127');
            $table->string('customer_phone','127');
            $table->string('graveyard','127');
            $table->string('graveyard_place','127');
            $table->text('description');
            $table->integer('order_id');
            $table->date('date_birth');
            $table->date('date_dead');
            $table->integer('funnel_id');
            $table->integer('stage_id');
            $table->dateTime('date_add');
            $table->dateTime('date_create');
            $table->dateTime('date_updated');
        });
    }

    public function down()
    {
        $this->schema->drop('deals');
    }
}
