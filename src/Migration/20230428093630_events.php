<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class Events extends Migration
{
    public function up()
    {
        $this->schema->create('events',function (Blueprint $table){
            $table->increments('id');
            $table->string('type_event','127');
            $table->string('text','127');
            $table->integer('user_id');
            $table->integer('deal_id');
            $table->dateTime('date_create');
        });
    }

    public function down()
    {
        $this->schema->drop('events');
    }
}
