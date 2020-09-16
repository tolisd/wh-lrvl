<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    //
    protected $table = 'tools';

    //file_url = χρεωστικό (αρχείο pdf)
    protected $fillable = [
        'code', 'name', 'description', 'comments', 'quantity', 'is_charged', 'file_url', 'employee_id',
    ];

    /*
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    */

    public function employee(){
        return $this->belongsTo('App\Employee', 'employee_id');
    }
}
