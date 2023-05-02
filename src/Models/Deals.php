<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Deals extends Model
{
    protected $table = 'deals';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'agent',
        'tag',
        'dead_name',
        'customer_name',
        'customer_phone',
        'graveyard',
        'graveyard_place',
        'description',
        'order_id',
        'date_birth',
        'date_dead',
        'stage_id',
        'date_add',
        'date_create',
        'date_updated'
    ];
}