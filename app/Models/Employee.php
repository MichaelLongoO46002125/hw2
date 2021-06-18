<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id',
        'salary',
        'job',
        'duty_start',
        'duty_end'
    ];

    protected $primaryKey= "person_id";
    protected $autoIncrement= false;

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}

?>
