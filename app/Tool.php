<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    //
    protected $table = 'tools';

    protected $fillable = [
        'name', 'code', 'description', 'quantity', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
