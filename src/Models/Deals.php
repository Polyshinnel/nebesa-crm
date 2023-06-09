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
        'sklad_id',
        'payed_sum',
        'total_sum',
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
        'funnel_id',
        'stage_id',
        'date_add',
        'date_create',
        'date_delivery',
        'date_updated',
    ];
}