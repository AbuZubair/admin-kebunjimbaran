<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "user";

    public $primaryKey = "rowID";

    const CREATED_AT = 'created_date';

    const UPDATED_AT = 'updated_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rowID',
        'firstName',
        'lastName',
        'password',
        'phoneNumber',
        'role',
        'username',    
        'created_date',
        'updated_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_date', 'updated_date'
    ];

    public function getId(){
        return $this->rowID;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getRole()
    {
        return $this->role;
    }

}
