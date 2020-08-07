<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    //
    protected $table = "imports";

    //protected $primaryKey = 'import_id';

    public function product(){
        return $this->belongsTo('App\Product', 'product_id'); //added the FK
    }

    public function assignment(){
        return $this->belongsTo('App\Assignment');
    }

}
