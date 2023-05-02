<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $table = 'order_details';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'order_id',
        'name',
        'position',
        'quantity',
        'price'
    ];
}