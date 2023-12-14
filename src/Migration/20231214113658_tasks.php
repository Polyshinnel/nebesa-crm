<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class Tasks extends Migration
{
    public function up()
    {
        $exists = $this->hasTable('tasks');

        if(!$exists) {
            $this->schema->create('tasks',function (Blueprint $table){
                $table->increments('id');
                $table->integer('stage_id');
                $table->integer('executor_id');
                $table->integer('controller_id');
                $table->string('task_title', '256');
                $table->dateTime('date_create');
                $table->date('expired_date');
            });
        }

    }

    public function down()
    {
        $this->schema->drop('tasks');
    }
}
