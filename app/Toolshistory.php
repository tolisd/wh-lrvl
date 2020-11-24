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

}
