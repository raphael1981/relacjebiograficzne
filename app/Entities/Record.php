<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Record extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'records';
    protected $fillable = ['title', 'alias', 'sort_string', 'signature', 'source', 'xmltrans', 'description', 'summary', 'duration', 'type', 'status', 'published_at'];


    public function interviewees()
    {
        return $this->morphToMany('App\Entities\Interviewee', 'intervieweegables');
    }


    public function redactors()
    {
        return $this->morphToMany('App\Entities\Redactor', 'redactorgables');
    }

    /*
     * Tabela periodgables zostałą oznaczona kluczem obcym epoki dla nagrania
     * Nagranie zostało przekształcone ubogacone przez informacje o epokach
     */
    public function periods()
    {
        return $this->morphToMany('App\Entities\Period', 'periodgables');
    }


    public function threads()
    {
        return $this->morphToMany('App\Entities\Thread', 'threadgables');
    }


    public function tags()
    {
        return $this->morphToMany('App\Entities\Tag', 'taggables');
    }


    public function intervals()
    {
        return $this->morphToMany('App\Entities\Interval', 'intervalgables');
    }



    public function fragments()
    {
        return $this->hasMany('App\Entities\Fragment');
    }


    public function fragmentsMorphedByMany()
    {
        return $this->morphedByMany('App\Entities\Fragment', 'recordgables');
    }


    public function recordsMorphToMany()
    {
        return $this->morphToMany('App\Entities\Record', 'recordgables');
    }

    public function recordsMorphedByMany()
    {
        return $this->morphedByMany('App\Entities\Record', 'recordgables');
    }

}
