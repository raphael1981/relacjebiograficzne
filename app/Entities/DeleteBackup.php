<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DeleteBackup extends Model
{
    protected $table = 'delete_backups';
    protected $fillable = ['model', 'data'];

}
