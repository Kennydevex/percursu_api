<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
 
    public $timestamps = false;
    protected $fillable = [
        'street', 
        'city',
        'postcode',
        'location_id',
        'folk_id'
    ];

    public function folk(){return $this->belongsTo('Folk');}
    
    public function location(){return $this->belongsTo('Location');}
}
