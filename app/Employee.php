<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = 'employees';

    //protected $primaryKey = 'id';


    protected $fillable = [
        'address', 'phone_number', 'user_id',
    ];

    //or, just use this line instead of filling in values for $fillable above
    //protected $guarded = [];


    /*1 Warehouse has_many Employees, and Each 1 Employee belongs to 1 Warehouse */
    public function warehouse(){
        return $this->belongsTo('App\Warehouse', 'warehouse_id');
    } //


    public function company(){
        return $this->belongsTo('App\Company', 'company_id');
    }


    public function tool(){
        return $this->hasMany('App\Tool');
    }

    /*
    public function accountant(){
        return $this->hasOne('App\Accountant', 'accountant_id'); //added the FK
    }
    */

    //employee is-a user, hasOne/belongsTo
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }


    public function import(){
        return $this->hasOne('App\Import');
    }

    public function export(){
        return $this->hasOne('App\Export');
    }

}
