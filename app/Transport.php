<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    //
    protected $table = "transports";

    //protected $primaryKey = 'company_id';

    protected $fillable = [
        'name', 'AFM', 'DOY', 'postal_code', 'address', 'city', 'email', 'phone_number', 'comments',
    ];

    //protected $guarded = [];


    //Relationships with other models/"database tables"
    public function import(){
        return $this->hasMany('App\Import');
    }

    public function export(){
        return $this->hasMany('App\Export');
    }
}
