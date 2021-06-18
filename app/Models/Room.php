<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_number',
        'matrimonial_bed',
        'single_bed',
        'wifi',
        'wifi_free',
        'minibar',
        'soundproofing',
        'swimming_pool',
        'private_bathroom',
        'air_conditioning',
        'sqm',
        'nightly_fee',
        'description'
    ];

    public function room_type()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function room_photo()
    {
        return $this->hasMany(RoomPhoto::class, null, "room_number");
    }

    public function rent()
    {
        return $this->hasMany(Rent::class, null, "room_number");
    }

}

?>
