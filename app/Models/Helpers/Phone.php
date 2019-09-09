<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public $timestamps = false;
    protected $fillable = ['number', 'type', 'folk_id'];

    public function folk(){return $this->belongsTo('Folk');}
}
