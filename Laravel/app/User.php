<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'phone_number', 'is_admin', 'birthdate'
    ];

    protected $dates = [
        'birthdate', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Get the reviews for the user.
     */
    public function reviews()
    {
        return $this->hasMany('Review');
    }

    /**
     * Get the articles for the user.
     */
    public function articles()
    {
        return $this->hasMany('Article');
    }
}
