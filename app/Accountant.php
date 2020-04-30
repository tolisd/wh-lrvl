<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountant extends Model
{
    //
    protected $table = "accountant";

    protected $primaryKey = 'accountant_id';


    public function employee(){
        return $this->belongsTo('App/Employee');
    }

}
