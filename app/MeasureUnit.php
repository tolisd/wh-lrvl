<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasureUnit extends Model
{
    //
    protected $table = 'measunits';

    protected $fillable = [
        'name', 'description',
    ];

    //Each m-u has many products (?), 1 product belongs to an m-u ( = "each product has one mu")
    public function product(){
        return $this->hasMany('App\Product');
    }


}
