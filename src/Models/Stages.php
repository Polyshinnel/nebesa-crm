<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Stages extends Model
{
    protected $table = 'stages';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'color_class',
        'visible'
    ];
}