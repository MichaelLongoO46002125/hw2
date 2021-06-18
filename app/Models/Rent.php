<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'night_stay',
        'nightly_fee',
        'check_in',
        'check_out'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, null, "room_number");
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

}

?>
