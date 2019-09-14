<?php

namespace App\Models\Percursu;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = ['status','featured',  'promo', 'folk_id'];

    public function folk()
    {
        return $this->belongsTo('Folk');
    }

    public function formations()
    {
        return $this->hasMany('Formation');
    }

    public function experiences()
    {
        return $this->hasMany('Experience');
    }

    public function sites()
    {
        return $this->hasMany('Site');
    }

    public function socials()
    {
        return $this->hasMany('Social');
    }

    public function skills()
    {
        return $this->hasMany('Skill');
    }

    public function charges()
    {
        return $this->belongsToMany('Charge');
    }

    public function getStatusAttribute($value)
    {
        if ($value) {
            return true;
        }
        return false;
    }

    public function getFeaturedAttribute($value)
    {
        if ($value) {
            return true;
        }
        return false;
    }

    public function getPromoAttribute($value)
    {
        if ($value) {
            return true;
        }
        return false;
    }

  
}
