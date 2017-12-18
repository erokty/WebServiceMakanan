<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models;

class Restaurant extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'file_name'
    ];

    protected $dates = [
      'created_at', 'updated_at'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restaurants';

    /**
     * Get the menus for the restaurant.
     */
    public function menus()
    {
        return $this->hasMany('App\Models\Menu');
    }

    /**
     * Get the reviews for the restaurant.
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
}
