<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //
    protected $table = "assignments";

    //protected $primaryKey = 'assignment_id';


    public function import(){
        return $this->hasMany('App\Import', 'import_id'); //added the FK
    }

    public function export(){
        return $this->hasMany('App\Export', 'export_id'); //added the FK
    }

    //the assignee, to whom I assigned the assignment to.
    //1 User has many Assignments, Each Assignment belongs to 1 User.
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    //1 Assignment, many products. Each product belongs to 1 Assignment.
    public function product(){
        return $this->hasMany('App\Product');
    }

}
