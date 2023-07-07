<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WorkerDeals extends Model
{
    protected $table = 'worker_deals';
    protected $fillable = [
        'id',
        'order_id',
        'name',
        'dead_name',
        'agent_name',
        'tag',
        'funeral_status',
        'status_id',
        'task_done',
        'tasks_totals',
        'money_to_pay',
        'total_money',
        'brigade_id',
        'date_create'
    ];
    public $timestamps = false;
}