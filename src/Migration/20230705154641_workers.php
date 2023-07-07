<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class Workers extends Migration
{
    public function up()
    {
        $this->schema->create('workers',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
        });
    }

    public function down()
    {
        $this->schema->drop('workers');
    }
}
