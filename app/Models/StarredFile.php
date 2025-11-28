<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StarredFile extends Model
{
     use HasFactory;

    protected $fillable = [
        'file_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
