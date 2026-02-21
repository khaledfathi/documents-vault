<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'file',
        'document_id',
    ];

    public function document (){
        $this->belongsTo(Document::class , 'document_id');
    }
}
