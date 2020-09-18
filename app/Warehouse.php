<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $table = 'warehouse';

    //protected $primaryKey = 'warehouse_id';

    protected $fillable = [
        'name', 'address', 'city', 'phone_number', 'email', 'company_id', 'foreman_id', 'worker_id',
    ];

    protected $casts = [
        'workers' => 'array',
    ];



    public function foreman(){
        return $this->hasOne('App\Employee', 'foreman_id'); //foreman is an Employee->User user_type
    }

    public function worker(){
        return $this->hasMany('App\Employee', 'worker_id'); //worker is an Employee->User user_type
    }

    /*
    public function employees(){
        return $this->hasMany('App\Employee', 'employee_id');
    }
    */

    public function company(){
        return $this->belongsTo('App\Company', 'company_id'); //added the FK
    }

    public function product(){
        return $this->belongsToMany('App\Product', 'product_warehouse', 'warehouse_id', 'product_id');
    }


    public function importassignment(){
        return $this->hasMany('App\ImportAssignment');
    }

    public function exportassignment(){
        return $this->hasMany('App\ExportAssignment');
    }

}
