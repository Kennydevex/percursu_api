<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Folk extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 
        'lastname',
        'gender',
        'avatar', 
        'cover', 
        'ic', 
        'nif', 
        'birthdate', 
        'category_id'
    ];

    public function user(){return $this->hasOne('User');}

    public function partner(){return $this->hasOne('Partner');}

    public function address()
    {
        return $this->hasOne('Address');
    }

    public function phones(){return $this->hasMany('Phone');}

    public function Socials(){return $this->hasMany('Social');}

    public function sites(){return $this->hasMany('Site');}

    public function couriers(){return $this->hasMany('Courier');}

    public function getFullnameAttribute() {
        return $this->name . ' ' . $this->lastname;
    }

    // public function getGenderAttribute($value){if ($value=='f') {return "Feminino";}if ($value=='m') {return "Masculino";}return "Não definido";}

    public function setIcAttribute($value) {
        if ( empty($value) ) {$this->attributes['ic'] = NULL;} 
        else {$this->attributes['ic'] = $value;}
    }

    public function setNifAttribute($value) {
        if ( empty($value) ) {$this->attributes['nif'] = NULL;} 
        else {$this->attributes['nif'] = $value;}
    }

    public function setNameAttribute($value){$this->attributes['name'] = ucwords($value);}

    public function setLastnameAttribute($value){$this->attributes['lastname'] = ucwords($value);}
}
