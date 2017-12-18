<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models;

class Grouping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected $dates = [
      'created_at', 'updated_at'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupings';

    /**
     * The menus that belong to the grouping.
     */
    public function menus()
    {
        return $this->belongsToMany('App\Models\Menu', 'menu_grouping', 'grouping_id', 'menu_id');
    }
}
