<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'id',
        'stage_id',
        'executor_id',
        'controller_id',
        'task_title',
        'date_create'
    ];
    public $timestamps = false;
}