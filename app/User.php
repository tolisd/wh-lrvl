<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'user_type', 'password', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //checks if the user belongs to a particular group
    public function user_type($user_type){
        $user_type = (array)$user_type;
        return in_array($this->user_type, $user_type);
    }

    //added roles
    
    public function isAdmin(){
        return \Auth::check() && \Auth::user()->user_type === 'super_admin';
    }

    public function isManager(){
        return \Auth::check() && \Auth::user()->user_type === 'company_ceo';
    }   

    public function isAccountant(){
        return \Auth::check() && \Auth::user()->user_type === 'accountant';
    }

    public function isWarehouseForeman(){
        return \Auth::check() && \Auth::user()->user_type === 'warehouse_foreman';
    }

    public function isWarehouseWorker(){
        return \Auth::check() && \Auth::user()->user_type === 'warehouse_worker';
    }
    
}
