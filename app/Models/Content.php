<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'date',
        'image_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];

    public function tag()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function person()
    {
        return $this->belongsToMany(Person::class, "favorites")->withTimestamps();
    }
}

?>
