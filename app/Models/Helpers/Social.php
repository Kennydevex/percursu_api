<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'link', 'partner_id'];

    public function partner(){return $this->belongsTo('Partner');}
}
