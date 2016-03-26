<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, JWTSubject
{
    use SoftDeletes, Authenticatable;

    protected $hidden = ['password'];

    protected $fillable = ['email'];


    public function getJWTIdentifier() {
        return $this->id;
    }

    public function getJWTCustomClaims() {
        return [];
    }
}