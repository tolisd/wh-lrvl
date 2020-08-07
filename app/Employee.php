<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = "employees";

    //protected $primaryKey = 'employee_id';


    public function warehouse(){
        return $this->hasMany('App\Warehouse');
    }

    public function accountant(){
        return $this->hasOne('App\Accountant', 'accountant_id'); //added the FK
    }

    //employee is-a user, hasOne/belongsTo
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
