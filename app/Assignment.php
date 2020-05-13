<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //
    protected $table = "assignments";

    protected $primaryKey = 'assignment_id';


    public function import(){
        return $this->hasOne('App/Import');
    }

    public function export(){
        return $this->hasOne('App/Export');
    }

}
