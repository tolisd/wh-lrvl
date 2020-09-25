<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = "category";

    //protected $primaryKey = 'category_id';

    protected $fillable = [
        'name', 'description',
    ];

    public function product(){
        return $this->hasMany('App\Product');
    }

    public function type(){
        return $this->hasMany('App\Type');
    }
}
