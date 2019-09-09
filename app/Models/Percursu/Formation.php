<?php

namespace App\Models\Percursu;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'designation',
        'from',
        'to',
        'ongoing',
        'institution',
        'subjects',
        'level',
        'country',
        'city',
        'attachment',
        'partner_id',
    ];

    public function partner()
    {
        return $this->belongsTo('Partner');
    }


    public function getOngoingAttribute($value)
    {
        if ($value) {
            return true;
        }
        return false;
    }

    // public function setDateAttribute( $value ) {
    //     $this->attributes['date'] = (new Carbon($value))->format('d/m/y');
    // }
}
