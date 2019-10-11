<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Period extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'periods';
    protected $fillable = ['name', 'alias'];

    /*
     * The table periodgables is morphed (przekształcona) by record
     * tabela pośrednia przekstałcana jest pod kątem trzymanego klucz rekordu (nagrania)
     */
    public function records()
    {
        return $this->morphedByMany('App\Entities\Record', 'periodgables');
    }


}
