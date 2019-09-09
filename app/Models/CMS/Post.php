<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Post extends Model
{
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(){return ['slug' => ['source' => 'title']];}

    protected $fillable = [
        'title',
        'summary',
        'body',
        'image',
        'published',
        'featured',
        'user_id',
        'category_id',
    ];


    public function user(){return $this->belongsTo('User');}

    public function category(){return $this->belongsTo('Category');}

    public function tags(){return $this->belongsToMany('Tag');}

    public function getStatusAttribute($value){
        if ($value) {return true;} return false;
    }

    public function getFeaturingAttribute($value)
    {
        if ($value) {return true;} return false;
    }

    public function companies()
    {
        return $this->belongsToMany('Company');
    }
}

