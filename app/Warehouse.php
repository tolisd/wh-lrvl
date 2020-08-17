<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $table = "warehouse";

    //protected $primaryKey = 'warehouse_id';

    protected $fillable = [
        'name', 'address', 'city', 'phone_number', 'email', 'company_id',
    ];


    public function employee(){
        return $this->hasMany('App\Employee');
    }

    public function company(){
        return $this->belongsTo('App\Company', 'company_id'); //added the FK
    }

    public function product(){
        return $this->hasMany('App\Product');
    }

}
