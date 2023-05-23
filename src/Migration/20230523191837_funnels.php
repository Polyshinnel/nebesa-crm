<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

final class Funnels extends Migration
{
    public function up()
    {
        $this->schema->create('funnels',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('tag','127');
        });

        $listAdds = [
            [
                'name' => 'Производство памятников',
                'tag' => 'Памятники'
            ],
            [
                'name' => 'Благоустройство',
                'tag' => 'Благоустройство'
            ]
        ];

        foreach ($listAdds as $listAdd){
            Capsule::table('funnels')->insert($listAdd);
        }
    }

    public function down()
    {
        $this->schema->drop('funnels');
    }
}
