<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportAssignment extends Model
{
    //
    protected $table = 'importassignments';

    protected $fillable = [
        'import_assignment_text', 'import_deadline', 'comments', 'uploaded_files', 'is_open', 'warehouse_id',
    ];

    protected $casts = [
        'uploaded_files' => 'array',
        'is_open' => 'boolean',
        'import_deadline' => 'datetime:d-m-Y H:i',
    ];


    public function import(){
        return $this->hasOne('App\Import');
    }

    public function warehouse(){
        return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }


}
