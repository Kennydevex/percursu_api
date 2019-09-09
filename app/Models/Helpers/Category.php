<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use Sluggable;
    public function sluggable(){return ['slug' => ['source' => 'title']];}

    protected $fillable = ['name', 'description', 'entity_id'];

    public function posts(){return $this->hasMany('Post');}

    public function companies(){return $this->hasMany('Company');}

    public function entity(){return $this->belongsTo('Entity');}

}
