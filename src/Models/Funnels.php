<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Funnels extends Model
{
    protected $table = 'funnels';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'tag'
    ];
}