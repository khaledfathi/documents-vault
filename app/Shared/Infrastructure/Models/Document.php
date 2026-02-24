<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'doc_number',
        'doc_date',
        'doc_expire_date',
        'visibility',
        'description',
        'user_id',
    ];

    public function user (){
        $this->belongsTo(User::class , 'user_id');
    }
    public function files (){
        $this->hasMany(File::class , 'document_id');
    }
    public function documentCategories (){
        $this->hasMany(DocumentCategory::class , 'category_id');
    }
}
