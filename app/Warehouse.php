<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $table = 'warehouse';

    //protected $primaryKey = 'warehouse_id';

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone_number',
        'email',
        'company_id',
        //'foreman_id', 'worker_id',
    ];

    /*
    protected $casts = [
        'worker_id' => 'array',
    ];
    */


    /*
    public function foreman(){
        return $this->hasOne('App\Employee', 'foreman_id'); //foreman is an Employee->User user_type
    }

    public function worker(){
        return $this->hasMany('App\Employee', 'worker_id'); //worker is an Employee->User user_type
    }
    */

    //N-to-M with Employees
    public function employees(){
        return $this->belongsToMany('App\Employee', 'employee_warehouse', 'warehouse_id', 'employee_id')
                    ->withTimestamps();
    }


    // public function employees(){
    //     return $this->hasMany('App\Employee');
    // }


    public function company(){
        return $this->belongsTo('App\Company', 'company_id'); //added the FK
    }

    //products is many-to-many with warehouses
    public function products(){
        return $this->belongsToMany('App\Product', 'product_warehouse', 'warehouse_id', 'product_id')
                    ->withPivot('quantity') //iot use increment and decrement i added the id column
                    ->withTimestamps(); //for the timestamps created_at updated_at, to be maintained.
    }
    //The third argument is the foreign key name of the model on which you are defining the relationship,
    //while the fourth argument is the foreign key name of the model that you are joining to.



    public function importassignment(){
        return $this->hasMany('App\ImportAssignment');
    }

    public function exportassignment(){
        return $this->hasMany('App\ExportAssignment');
    }

}
