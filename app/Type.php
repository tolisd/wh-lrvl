<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    protected $table = "types";

    protected $fillable = [
        'name', 'description', 'category_id',
    ];

    //1 Type has many Product(s), Each Product is of 1 Type.
    public function product(){
        return $this->hasMany('App\Product');
    }

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

}
