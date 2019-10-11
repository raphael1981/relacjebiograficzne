<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Fragment extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'fragments';
    protected $fillable = ['record_id','content','start','ord','created_at','updated_at'];


    public function record()
    {
        return $this->belongsTo('App\Entities\Record');
    }


    public function interviewees()
    {
        return $this->morphToMany('App\Entities\Interviewee', 'intervieweegables');
    }


    public function periods()
    {
        return $this->morphToMany('App\Entities\Period', 'periodgables');
    }


    public function tags()
    {
        return $this->morphToMany('App\Entities\Tag', 'taggables');
    }

    public function places()
    {
        return $this->morphToMany('App\Entities\Place', 'placegables');
    }

    public function intervals()
    {
        return $this->morphToMany('App\Entities\Interval', 'intervalgables');
    }


    public function redactors()
    {
        return $this->morphToMany('App\Entities\Redactor', 'redactorgables');
    }


    public function galleries()
    {
        return $this->morphToMany('App\Entities\Gallery', 'gallerygables');
    }


    public function records()
    {
        return $this->morphToMany('App\Entities\Record', 'recordgables');
    }

}
