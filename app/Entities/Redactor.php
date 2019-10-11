<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Redactor extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'redactors';
    protected $fillable = ['name', 'surname', 'email', 'profession', 'status'];


    public function records()
    {
        return $this->morphedByMany('App\Entities\Record', 'redactorgables');
    }

    public function fragments()
    {
        return $this->morphedByMany('App\Entities\Fragment', 'redactorgables');
    }



}
