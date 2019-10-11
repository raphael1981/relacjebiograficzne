<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class MonitorIndx extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'monitor_indxes';

    protected $fillable = ['type','start_at','end_at','status'];

}
