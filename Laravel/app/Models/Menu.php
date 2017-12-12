<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models;

class Menu extends Model
{
  use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price' ,'restaurant_id'
    ];

    protected $dates = [
      'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'restaurant_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * Get the restaurant that owns the menu.
     */
    public function restaurant()
    {
        return $this->belongsTo('Restaurant');
    }

    /**
     * The groupings that belong to the menu.
     */
    public function groupings()
    {
        return $this->belongsToMany('Grouping');
    }
}
