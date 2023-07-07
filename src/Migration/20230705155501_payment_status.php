<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class PaymentStatus extends Migration
{
    public function up()
    {
        $this->schema->create('payment_status',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('color_class','127');
        });
    }

    public function down()
    {
        $this->schema->drop('payment_status');
    }
}
