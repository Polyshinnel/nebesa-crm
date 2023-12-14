<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;

class TaskEventModel extends Model
{
    protected $table = 'task_events';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'task_id',
        'type_event',
        'event_text',
        'user_id',
        'date_create'
    ];
}