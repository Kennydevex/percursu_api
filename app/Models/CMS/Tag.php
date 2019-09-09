<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Tag extends Model
{
    use Sluggable;
    public function sluggable(){return ['slug' => ['source' => 'title']];}

    public $timestamps = false;
    protected $fillable = ['name', 'description'];

    public function posts(){return $this->belongsToMany('Post');}
}
