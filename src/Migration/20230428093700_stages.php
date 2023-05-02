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
        });

        $listAdds = [
            [
                'name' => 'Заказ поставщику',
                'color_class' => 'base',
                'visible' => 1
            ],
            [
                'name' => 'Соглас. ретуши',
                'color_class' => 'stage-2',
                'visible' => 1
            ],
            [
                'name' => 'Приемка(склад)',
                'color_class' => 'stage-3',
                'visible' => 1
            ],
            [
                'name' => 'Гравировка',
                'color_class' => 'stage-4',
                'visible' => 1
            ],
            [
                'name' => 'Приемка',
                'color_class' => 'stage-5',
                'visible' => 1
            ],
            [
                'name' => 'Отмененный',
                'color_class' => 'base',
                'visible' => 0
            ],
            [
                'name' => 'Успешно выполненный',
                'color_class' => 'base',
                'visible' => 0
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
