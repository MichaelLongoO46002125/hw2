<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyCookie extends Model
{
    protected $table= "cookies";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'expires',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}

?>
