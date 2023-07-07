<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentProducts extends Model
{
    protected $table = 'payment_products';
    protected $fillable = [
        'id',
        'name',
        'category_id',
        'price'
    ];
    public $timestamps = false;
}