<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    protected $table = "types";

    protected $fillable = [
        'name', 'description',
    ];

    //1 Type has many Product(s), Each Product has 1 Type.
    public function product(){
        return $this->hasMany('App\Product');
    }

}
