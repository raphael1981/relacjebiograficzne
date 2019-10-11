<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Article extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'articles';
    protected $fillable = ['category_id', 'title', 'alias', 'intro_image', 'disk', 'intro', 'content', 'external_url', 'target_type', 'featured', 'main', 'status','published_at'];

    public function category(){
        return $this->belongsTo('App\Entities\Category');
    }

    public function galleries()
    {
        return $this->morphToMany('App\Entities\Gallery', 'gallerygables');
    }

}
