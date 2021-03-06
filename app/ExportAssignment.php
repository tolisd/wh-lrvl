<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExportAssignment extends Model
{
    //
    protected $table = 'exportassignments';

    protected $fillable = [
        'export_assignment_text', 'export_deadline', 'comments', 'uploaded_files', 'is_open', 'warehouse_id', 'user_id',
    ];

    protected $casts = [
        'uploaded_files' => 'array',
        'is_open' => 'boolean',
        'export_deadline' => 'datetime:d-m-Y H:i',
    ];


    public function export(){
        return $this->hasOne('App\Export');
    }

    public function warehouse(){
        return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
