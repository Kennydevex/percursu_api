<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'status'];
    protected $table = 'entities';
}
