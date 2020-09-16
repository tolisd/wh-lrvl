<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $table = "company";

    //protected $primaryKey = 'company_id';

    protected $guarded = [];


    public function warehouse(){
        return $this->hasMany('App\Warehouse');
    }

    public function employee(){
        return $this->hasMany('App\Employee');
    }

    public function import(){
        return $this->hasMany('App\Import');
    }

    public function export(){
        return $this->hasMany('App\Export');
    }

}
