<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $table = "company";

    //protected $primaryKey = 'company_id';


    public function warehouse(){
        return $this->hasMany('App\Warehouse');
    }

    public function employee(){
        return $this->hasMany('App\Employee');
    }

}
