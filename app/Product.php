<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $table = "products";

    //protected $primaryKey = 'product_id';

    protected $fillable = [
        'name', 'description', 'quantity', 'comments', 'category_id',
    ];


    public function category(){
        return $this->belongsTo('App\Category', 'category_id'); //added the FK
    }

    public function import(){
        return $this->hasMany('App\Import');
    }

    public function export(){
        return $this->hasMany('App\Export');
    }

    //added for getting the type via product
    public function type(){
        return $this->belongsTo('App\Type', 'type_id');
    }

    //1 Assignment has many products.
    public function assignment(){
        return $this->belongsTo('App\Assignment', 'assignment_id');
    }

}
