<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscriptions extends Model
{
    protected $table = 'inscriptions';

    protected $fillable = [
        'date_inscription',
        'user_id',
        'course_id'
        ];
}
