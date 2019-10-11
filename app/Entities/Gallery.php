<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Gallery extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'galleries';
    protected $fillable = ['name', 'alias', 'description', 'photos', 'regexstamp', 'destination', 'mode', 'disk', 'status'];

    public function pictures()
    {
        return $this->morphToMany('App\Entities\Picture', 'picturegables');
    }

    public function articles()
    {
        return $this->morphedByMany('App\Entities\Article', 'picturegables');
    }

    public function fragments()
    {
        return $this->morphedByMany('App\Entities\Fragment', 'gallerygables');
    }

}
