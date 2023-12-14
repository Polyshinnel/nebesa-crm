<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;

class TaskStagesModel extends Model
{
    protected $table = 'task_stages';
    protected $fillable = [
        'id',
        'name',
        'color_class',
        'visible'
    ];
    public $timestamps = false;
}