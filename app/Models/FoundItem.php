<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    protected $fillable = [
        'user_id', 
        'room_id', 
        'item_name', 
        'found_date',
        'item_image_path', // <--- Add this line
    ];

    protected $casts = [
        'found_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}