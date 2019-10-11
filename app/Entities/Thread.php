<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Thread extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'threads';
    protected $fillable = ['name', 'alias'];


    public function records()
    {
        return $this->morphedByMany('App\Entities\Record', 'threadgables');
    }

}
