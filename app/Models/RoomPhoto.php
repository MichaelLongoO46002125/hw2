<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPhoto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo_path'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, null, "room_number");
    }

}

?>
