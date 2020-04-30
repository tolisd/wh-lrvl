<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $table = "products";

    protected $primaryKey = 'product_id';
    

    public function category(){
        return $this->belongsTo('App/Category');
    }

    public function import(){
        return $this->hasMany('App/Import');
    }

    public function export(){
        return $this->hasMany('App/Export');
    }

}
