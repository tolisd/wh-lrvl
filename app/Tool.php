<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    //
    protected $table = 'tools';

    protected $fillable = [
        'code', 'name', 'description', 'comments', 'quantity', 'is_charged', 'user_id',
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
