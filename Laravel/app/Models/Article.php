<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'user_id'
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
        'user_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * Get the user that owns the article.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the article pics for the article.
     */
    public function articlePics()
    {
        return $this->hasMany('App\Models\ArticlePicture');
    }
}
