<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Tag extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'tags';
    protected $fillable = ['name'];


    public function records()
    {
        return $this->morphedByMany('App\Entities\Record', 'taggables');
    }

    public function fragments()
    {
        return $this->morphedByMany('App\Entities\Fragment', 'taggables');
    }


}
