<?php

namespace App\Models\Percursu;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Company extends Model
{
    use Sluggable;
    public function sluggable(){return ['slug' => ['source' => 'title']];}

    public $timestamps = false;
    protected $fillable = [
        'name',
        'slogan',
        'presentation',
        'logo',
        'cover',
        'start',
        'status',
        'category_id',
        'user_id'
    ];

    public function user(){return $this->belongsTo('User');}

    public function category(){return $this->belongsTo('Category');}

    public function posts(){return $this->belongsToMany('Post');}

}




