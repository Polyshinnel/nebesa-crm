<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;


final class Stages extends Migration
{
    public function up()
    {
        $this->schema->create('stages',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('color_class','127');
            $table->integer('visible');
            $table->integer('funnel_id');
        });

        $listAdds = [
            [
                'name' => 'Заказ поставщику',
                'color_class' => 'base',
                'visible' => 1,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Соглас. ретуши',
                'color_class' => 'stage-2',
                'visible' => 1,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Приемка(склад)',
                'color_class' => 'stage-3',
                'visible' => 1,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Гравировка',
                'color_class' => 'stage-4',
                'visible' => 1,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Приемка',
                'color_class' => 'stage-5',
                'visible' => 1,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Отмененный',
                'color_class' => 'canceled',
                'visible' => 0,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Успешно выполненный',
                'color_class' => 'success',
                'visible' => 0,
                'funnel_id' => 1,
            ],

            [
                'name' => 'Бланк выдан',
                'color_class' => 'base',
                'visible' => 0,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Бетонные работы',
                'color_class' => 'stage-2',
                'visible' => 0,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Установка памятника',
                'color_class' => 'stage-3',
                'visible' => 0,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Приемка',
                'color_class' => 'stage-4',
                'visible' => 0,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Успешно выполненный',
                'color_class' => 'success',
                'visible' => 0,
                'funnel_id' => 1,
            ],
            [
                'name' => 'Отмененный',
                'color_class' => 'canceled',
                'visible' => 0,
                'funnel_id' => 1,
            ],
        ];

        foreach ($listAdds as $listAdd){
            Capsule::table('stages')->insert($listAdd);
        }
    }

    public function down()
    {
        $this->schema->drop('stages');
    }
}
