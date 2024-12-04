<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    protected $table= 'profesors';
    protected $fillable = [
        'name',
        'lastName',
        'email',
        'password'
    ];
}
