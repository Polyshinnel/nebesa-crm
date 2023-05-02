<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'date_create'
    ];
}