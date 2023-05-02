<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class Users extends Migration
{
    public function up()
    {
        $this->schema->create('users',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('password','127');
            $table->string('fullname','127');
            $table->string('avatar','127');
        });
    }

    public function down()
    {
        $this->schema->drop('users');
    }
}
