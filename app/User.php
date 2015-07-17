<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use App\Common\BaseModel;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends BaseModel implements AuthenticatableContract
{
    use Authenticatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

}
