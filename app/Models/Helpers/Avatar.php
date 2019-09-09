<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
