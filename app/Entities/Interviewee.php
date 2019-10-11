<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Interviewee extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'interviewees';
    protected $fillable = ['name','surname', 'sort_string', 'biography', 'portrait', 'disk', 'status'];


    public function records()
    {
        return $this->morphedByMany('App\Entities\Record', 'intervieweegables');
    }


    public function pictures()
    {
        return $this->morphToMany('App\Entities\Picture', 'picturegables');
    }


}
