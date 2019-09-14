<?php

namespace App\Models\System;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

// class User extends Authenticatable implements JWTSubject
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;

    // protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'username',
        'password',
        'status',
        'category_id',
        'folk_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime',];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function folk()
    {
        return $this->belongsTo('Folk');
    }

    public function posts()
    {
        return $this->hasMany('Post');
    }

    public function companies()
    {
        return $this->hasMany('Company');
    }

    // public function permissions()
    // {
    //     return $this->belongsToMany('Permission');
    // }

    // public function roles()
    // {
    //     return $this->belongsToMany('Role');
    // }

    public function getStatusAttribute($value)
    {
        if ($value) {
            return true;
        }
        return false;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
}
