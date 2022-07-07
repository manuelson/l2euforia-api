<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
/**
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $created_time
 * @property integer $lastactive
 * @property boolean $accessLevel
 * @property string $lastIP
 * @property boolean $lastServer
 * @property string $pcIp
 * @property string $hop1
 * @property string $hop2
 * @property string $hop3
 * @property string $hop4
 */
class Account extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject 
{

    use Authenticatable, Authorizable;


    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'login';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['password', 'email', 'created_time', 'lastactive', 'accessLevel', 'lastIP', 'lastServer', 'pcIp', 'hop1', 'hop2', 'hop3', 'hop4'];

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
}
