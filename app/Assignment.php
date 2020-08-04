<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //
    protected $table = "assignments";

    //protected $primaryKey = 'assignment_id';


    public function import(){
        return $this->hasMany('App/Import');
    }

    public function export(){
        return $this->hasMany('App/Export');
    }

}
