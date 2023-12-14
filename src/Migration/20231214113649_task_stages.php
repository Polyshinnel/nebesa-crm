<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

final class TaskStages extends Migration
{
    public function up()
    {
        $exists = $this->hasTable('task_stages');

        if(!$exists) {
            $this->schema->create('task_stages',function (Blueprint $table){
                $table->increments('id');
                $table->string('name','127');
                $table->string('color_class','127');
                $table->integer('visible');
            });

            $listAdds = [
                [
                    'name' => 'Новая задача',
                    'color_class' => 'base',
                    'visible' => 1,
                ],
                [
                    'name' => 'Задача выполняется',
                    'color_class' => 'stage-2',
                    'visible' => 1,
                ],
                [
                    'name' => 'Сдана на проверку',
                    'color_class' => 'stage-3',
                    'visible' => 1,
                ],
                [
                    'name' => 'Отменена',
                    'color_class' => 'canceled',
                    'visible' => 0,
                ],
                [
                    'name' => 'Успешно выполнена',
                    'color_class' => 'success',
                    'visible' => 0,
                ],
            ];

            foreach ($listAdds as $listAdd){
                Capsule::table('task_stages')->insert($listAdd);
            }
        }

    }

    public function down()
    {
        $this->schema->drop('task_stages');
    }
}
