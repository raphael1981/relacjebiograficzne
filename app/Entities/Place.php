<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Place extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'places';

    protected $fillable = ['name', 'alias', 'lat', 'lng'];

    public function fragments()
    {
        return $this->morphedByMany('App\Entities\Fragment', 'placegables');
    }
}
