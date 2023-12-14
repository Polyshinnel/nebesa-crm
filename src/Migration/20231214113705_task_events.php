<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class TaskEvents extends Migration
{
    public function up()
    {
        $exists = $this->hasTable('task_events');

        if(!$exists) {
            $this->schema->create('task_events',function (Blueprint $table){
                $table->increments('id');
                $table->integer('task_id');
                $table->string('type_event','127');
                $table->string('event_text','512');
                $table->integer('user_id');
                $table->dateTime('date_create');
            });
        }

    }

    public function down()
    {
        $this->schema->drop('task_events');
    }
}
