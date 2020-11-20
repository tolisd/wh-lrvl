<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    //
    protected $table = "imports";

    //protected $primaryKey = 'id';

    protected $fillable = [
        'delivered_on',
        'delivery_address',
        'discrete_description',
        'hours_worked',
        'chargeable_hours_worked',
        'shipment_bulletin',
        //'shipment_address',
        'vehicle_reg_no',

        //'product_id',
        'employee_id',
        'company_id',
        'transport_id',
        'importassignment_id',
    ];

    protected $casts = [
        'delivered_on' => 'datetime:d-m-Y H:i:s',
    ];



    public function products(){
        return $this->belongsToMany('App\Product', 'import_product', 'import_id', 'product_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /*
    public function product(){
        return $this->belongsTo('App\Product', 'product_id'); //added the FK
    }
    */

    /*
    public function assignment(){
        return $this->belongsTo('App\Assignment');
    }
    */

    public function import_assignment(){
        return $this->belongsTo('App\ImportAssignment', 'importassignment_id');
    }

    //the worker or foreman to whom the assignment is assigned to
    public function employee(){
        return $this->belongsTo('App\Employee', 'employee_id');
    }

    //the company
    public function company(){
        return $this->belongsTo('App\Company', 'company_id');
    }

    //the shipping company
    public function transport(){
        return $this->belongsTo('App\Transport', 'transport_id');
    }


}
