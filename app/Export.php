<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    //
    protected $table = "exports";

    //protected $primaryKey = 'export_id';

    public function product(){
        return $this->belongsTo('App\Product', 'product_id'); //added the FK
    }

    public function assignment(){
        return $this->belongsTo('App\Assignment');
    }
}
