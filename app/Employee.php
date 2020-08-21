<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = "employees";

    //protected $primaryKey = 'employee_id';

    protected $fillable = [
        'address', 'phone_number', 'email', 'company_id', 'warehouse_id', 'user_id',
    ];



    public function warehouse(){
        return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }

    public function company(){
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function accountant(){
        return $this->hasOne('App\Accountant', 'accountant_id'); //added the FK
    }

    //employee is-a user, hasOne/belongsTo
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
