<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $table = "products";

    //protected $primaryKey = 'product_id';

    protected $fillable = [
        'code', 'name', 'description', 'quantity', 'comments', 'category_id', 'type_id', 'warehouse_id',
    ];

    public function warehouse(){
        return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }

    public function import(){
        return $this->hasMany('App\Import');
    }

    public function export(){
        return $this->hasMany('App\Export');
    }

    public function category(){
        return $this->belongsTo('App\Category', 'category_id'); //added the FK
    }

    //added for getting the type via product
    public function type(){
        return $this->belongsTo('App\Type', 'type_id');
    }

    public function measureunit(){
        return $this->belongsTo('App\MeasureUnit', 'measunit_id');
    }

    //1 Assignment has many products.
    public function assignment(){
        return $this->belongsTo('App\Assignment', 'assignment_id');
    }

}
