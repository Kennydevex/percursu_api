<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $timestamps = false;
    protected $fillable = ['link', 'description', 'partner_id'];

    public function partner(){return $this->belongsTo('Partner');}
}
