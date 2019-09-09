<?php

namespace App\Models\Percursu;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 
        'description', 
        'type'
    ];

    public function user(){return $this->belongsTo('User');}

}
