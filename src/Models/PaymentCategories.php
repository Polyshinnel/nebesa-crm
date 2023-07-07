<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentCategories extends Model
{
    protected $table = 'payment_categories';
    protected $fillable = [
        'id',
        'name'
    ];
    public $timestamps = false;
}