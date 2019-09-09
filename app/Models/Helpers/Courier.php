<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    public $timestamps = false;
    protected $fillable = ['email', 'type', 'folk_id'];

    public function folk(){return $this->belongsTo('Folk');}
}
