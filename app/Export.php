<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    //
    protected $table = "exports";

    //protected $primaryKey = 'export_id';

    protected $fillable = [
        'delivered_on',
        'shipment_address',
        'destination_address',
        'item_description',
        'hours_worked',
        'chargeable_hours_worked',
        //'shipping_company',  //remove this? i already have company_id...
        'shipment_bulletin',
        'vehicle_reg_no',

        //'product_id',
        'company_id',
        'transport_id',
        'exportassignment_id',
        'employee_id',
    ];

    protected $casts = [
        'delivered_on' => 'datetime:d-m-Y H:i:s',
    ];


    public function products(){
        return $this->belongsToMany('App\Product', 'export_product', 'export_id', 'product_id')
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

    public function export_assignment(){
        return $this->belongsTo('App\ExportAssignment', 'exportassignment_id');
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
