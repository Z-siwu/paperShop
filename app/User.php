<?php

namespace App;

use App\Models\Dorm;
use App\Models\ServiceRange;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const USER_ROLE_DELIVERY = 2;//配送员

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','openid','nickname','avatar','unionid','login_ip','login_time',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','state','role',
    ];

    public function dorms()
    {
        return $this->belongsToMany(Dorm::class, 'service_range', 'delivery_uid', 'did');
    }
    //修改电话信息
    public function userInfo()
    {
        $q = \Auth::user()->openid;
        return User::where('openid', $q)->get();

    }
}
