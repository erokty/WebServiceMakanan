<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models;

class ArticlePicture extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name', 'article_id'
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
        'article_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_pictures';

    /**
     * Get the article that owns the picture.
     */
    public function article()
    {
        return $this->belongsTo('App\Models\Article');
    }
}
