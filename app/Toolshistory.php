<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Toolshistory extends Model
{
    //
    protected $table = 'toolshistory';

    protected $fillable = [
        'charged_at', 'uncharged_at', 'tool_id',
    ];

    //or, instead of filling in individual values in $fillable, just write the following
    /*
    protected $guarded = [];
    */

    protected $casts = [
        'charged_at'   => 'array',
        'uncharged_at' => 'array',
    ];


    public function tool(){
        return $this->belongsTo('App\Tool', 'tool_id');
    }


    // hasManyThrough works only for relations like this:
    // A hasMany/hasOne B, B hasMany/hasOne C, THEN ==> A hasManyThrough C (through B)

    //hasManyThrough, the intermediary being 'Tool' model
    public function employee_mt(){
        return $this->hasManyThrough('App\Employee', 'App\Tool', 'employee_id', 'user_id', 'id', 'id');
    }

}
