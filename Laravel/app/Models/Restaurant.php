<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models;

class Restaurant extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'image_url'
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
        return $this->hasMany('Menu');
    }
}
