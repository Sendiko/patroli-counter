<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // Allow these columns to be filled
    protected $fillable = ['code', 'name'];
}
