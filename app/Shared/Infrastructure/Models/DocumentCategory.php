<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = [
        'document_id',
        'category_id',
    ];

    public function document (){
        $this->belongsTo(Document::class , 'document_id');
    }
    public function category (){
        $this->belongsTo(Category::class , 'category_id');
    }
}
