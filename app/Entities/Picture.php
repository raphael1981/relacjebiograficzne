<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Picture extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'pictures';
    protected $fillable = ['source', 'disk', 'description', 'keywords'];


    public function interviewees()
    {
        return $this->morphedByMany('App\Entities\Interviewee', 'picturegables');
    }

    public function galeries()
    {
        return $this->morphedByMany('App\Entities\Gallery', 'gallerygables');
    }

}
