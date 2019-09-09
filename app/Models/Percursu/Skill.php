<?php

namespace App\Models\Percursu;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'name', 
        'description',
        'partner_id',
    ];

    public function partner(){return $this->belongsTo('Partner');}

}
