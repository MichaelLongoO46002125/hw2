<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipe',
        'accomodation'
    ];

    public function room()
    {
        return $this->hasMany(Room::class);
    }

}

?>
