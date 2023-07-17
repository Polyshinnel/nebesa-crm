<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WorkerDealDetailEvents extends Model
{
    protected $table = 'worker_deal_detail_events';
    protected $fillable = [
        'id',
        'deal_id',
        'event_text',
        'date_create'
    ];
    public $timestamps = false;
}