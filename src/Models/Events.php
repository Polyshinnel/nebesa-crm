<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'events';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'type_event',
        'text',
        'user_id',
        'deal_id',
        'date_create'
    ];
}