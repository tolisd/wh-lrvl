<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $table = 'warehouse';

    //protected $primaryKey = 'warehouse_id';

    protected $fillable = [
        'name', 'address', 'city', 'phone_number', 'email', 'company_id', 'workers', 'foreman'
    ];  //foreman & worker will be produced by the inverse relationship...so no FKs needed in this table!

    protected $casts = [
        'workers' => 'array',
    ];



    public function employees(){
        return $this->hasMany('App\Employee');
    }

    /*
    public function employees(){
        return $this->hasMany('App\Employee');
    }
    */

    public function company(){
        return $this->belongsTo('App\Company', 'company_id'); //added the FK
    }

    public function product(){
        return $this->hasMany('App\Product');
    }



    public function importassignment(){
        return $this->hasMany('App\ImportAssignment');
    }

    public function exportassignment(){
        return $this->hasMany('App\ExportAssignment');
    }

}
