<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = "employees";

    protected $primaryKey = 'employee_id';

    public function warehouse(){
        return $this->hasMany('App/Warehouse');
    }

    public function accountant(){
        return $this->hasOne('App/Accountant');
    }

}
