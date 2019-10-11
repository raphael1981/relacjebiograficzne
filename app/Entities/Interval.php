<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Interval extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['name', 'alias', 'begin', 'end'];

    public function records()
    {
        return $this->morphedByMany('App\Entities\Record', 'intervalgables');
    }

    public function fragments()
    {
        return $this->morphedByMany('App\Entities\Fragment', 'intervalgables');
    }

}
