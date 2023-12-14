<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class PaymentProducts extends Migration
{
    public function up()
    {
        $exists = $this->hasTable('payment_products');

        if(!$exists) {
            $this->schema->create('payment_products',function (Blueprint $table){
                $table->increments('id');
                $table->string('name','127');
                $table->integer('category_id');
                $table->decimal('price',10, 2);
            });
        }

    }

    public function down()
    {
        $this->schema->drop('payment_products');
    }
}
