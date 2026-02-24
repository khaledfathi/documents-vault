<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];
    
    public function documentCategories (){
        $this->hasMany(DocumentCategory::class , 'document_id');
    }
}
