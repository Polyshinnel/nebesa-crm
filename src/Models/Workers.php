<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Workers extends Model
{
    protected $table = 'workers';
    protected $fillable = [
        'id',
        'name'
    ];
    public $timestamps = false;
}