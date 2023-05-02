<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'username',
        'password',
        'fullname',
        'avatar'
    ];
}