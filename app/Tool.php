<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    //
    protected $table = 'tools';

    //file_url = χρεωστικό (αρχείο pdf)
    protected $fillable = [
        'code', 'name', 'description', 'comments', 'quantity', 'is_charged', 'file_url', 'employee_id', 'charger_id'
    ];

    //or, instead of filling in individual values in $fillable, just write the following
    /*
    protected $guarded = [];
    */

    protected $casts = [
        'is_charged' => 'boolean',
    ];

    /*
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    */

    // protected $dates = [
    //     'updated_at', 'created_at',
    // ];



    //charged (with a tool) employee
    public function employee(){
        return $this->belongsTo('App\Employee', 'employee_id');
    }

    //the charging (some other employee) employee
    public function charger(){
        return $this->belongsTo('App\Employee', 'charger_id');
    }




    public function toolshistory(){
        return $this->hasOne('App\Toolhistory');
    }
}
