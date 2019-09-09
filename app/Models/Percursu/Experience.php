<?php

namespace App\Models\Percursu;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'task',
        'from',
        'to',
        'ongoing',
        'employer',
        'responsibility',
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
