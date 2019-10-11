<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    protected $table = 'customers';
    protected $fillable = ['name', 'surname', 'email', 'password', 'type', 'institution_name', 'register_target', 'phone', 'status', 'verification_token', 'expire_remember_token'];
    protected $dates = ['deleted_at'];

}
