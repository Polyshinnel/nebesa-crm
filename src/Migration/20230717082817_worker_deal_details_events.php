<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class WorkerDealDetailsEvents extends Migration
{
    public function up()
    {
        $exists = $this->hasTable('worker_deal_detail_events');

        if(!$exists) {
            $this->schema->create('worker_deal_detail_events',function (Blueprint $table){
                $table->increments('id');
                $table->integer('deal_id');
                $table->string('event_text','512');
                $table->dateTime('date_create');
            });
        }

    }

    public function down()
    {
        $this->schema->drop('worker_deal_detail_events');
    }
}
