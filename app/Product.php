<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $table = 'products';

    //protected $primaryKey = 'product_id';

    protected $fillable = [
        'code',
        'name',
        'description',
        //'quantity',
        'comments',
        'category_id',
        'type_id',
        'measunit_id',
        //'warehouse_id',
    ];

    //warehouses is many-to-many with products
    public function warehouses(){
        return $this->belongsToMany('App\Warehouse', 'product_warehouse', 'product_id', 'warehouse_id')
                    ->withPivot('quantity') //for increment and decrement i added the id column
                    ->withTimestamps(); //for the timestamps created_at updated_at, to be maintained.
    }
    //The third argument is the foreign key name of the model on which you are defining the relationship,
    //while the fourth argument is the foreign key name of the model that you are joining to.

    public function imports(){
        return $this->belongsToMany('App\Import', 'import_product', 'product_id', 'import_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function exports(){
        return $this->belongsToMany('App\Export', 'export_product', 'product_id', 'export_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /*
    public function import(){
        return $this->hasMany('App\Import');
    }


    public function export(){
        return $this->hasMany('App\Export');
    }
    */


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
    /*
    public function assignment(){
        return $this->belongsTo('App\Assignment', 'assignment_id');
    }
    */

}
