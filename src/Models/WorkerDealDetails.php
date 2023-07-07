<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WorkerDealDetails extends Model
{
    protected $table = 'worker_deal_details';
    protected $fillable = [
        'id',
        'worker_deal',
        'product_name',
        'quantity',
        'price',
        'total'
    ];
    public $timestamps = false;
}