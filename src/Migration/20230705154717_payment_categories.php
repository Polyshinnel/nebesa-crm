<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class PaymentCategories extends Migration
{
    public function up()
    {
        $exists = $this->hasTable('payment_categories');

        if(!$exists) {
            $this->schema->create('payment_categories',function (Blueprint $table){
                $table->increments('id');
                $table->string('name','127');
            });
        }

    }

    public function down()
    {
        $this->schema->drop('payment_categories');
    }
}
