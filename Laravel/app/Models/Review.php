<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'content', 'rating', 'user_id', 'restaurant_id'
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
        'user_id', 'restaurant_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    /**
     * Get the restaurant that owns the review.
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }
}
