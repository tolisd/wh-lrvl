<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $table = "warehouse";

    //protected $primaryKey = 'warehouse_id';


    public function employee(){
        return $this->belongsTo('App/Employee');
    }

    public function company(){
        return $this->belongsTo('App/Company');
    }

}
