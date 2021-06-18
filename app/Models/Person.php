<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Person extends Model //Authenticatable
{
    //use HasFactory, Notifiable;
    protected $table = "persons";

    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'phone_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pivot'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function cookie()
    {
        return $this->hasOne(MyCookie::class);
    }

    public function rent()
    {
        return $this->hasMany(Rent::class);
    }

    public function content()
    {
        return $this->belongsToMany(Content::class, "favorites")->withTimestamps();
    }
}

?>
