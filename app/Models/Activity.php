<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ActivityType;

class Activity extends Model
{
    protected $fillable = [
        'user_id', 'room_id', 'type', 'note', 
        'proof_image_path', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'type' => ActivityType::class, // Cast to Enum
    ];

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
