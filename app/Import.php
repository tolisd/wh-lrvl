<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    //
    protected $table = "imports";

    //protected $primaryKey = 'import_id';

    public function product(){
        return $this->belongsTo('App/Product');
    }

    public function assignment(){
        return $this->belongsTo('App/Assignment');
    }

}
