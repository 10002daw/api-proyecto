<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function communities()
    {
        return $this->belongsToMany(Community::class)
            ->withPivot('admin', 'owner')
            ->withTimestamps();
    }

    public function adminCommunities()
    {
        return $this->belongsToMany(Community::class)
            ->withPivot('admin', 'owner')
            ->withTimestamps()
            ->wherePivot('admin', 1);
    }

    public function ownerCommunities()
    {
        return $this->belongsToMany(Community::class)
            ->withPivot('admin', 'owner')
            ->withTimestamps()
            ->wherePivot('owner', 1);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function isPartOfCommunity(Community $community)
    {
        return $this->communities->contains($community->id);
    }

    public function isAdminOfCommunity(Community $community)
    {
        return $this->adminCommunities->contains($community->id);
    }

    public function isOwnerOfCommunity(Community $community)
    {
        return $this->ownerCommunities->contains($community->id);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->admin;
    }
}
