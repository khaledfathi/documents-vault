<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key' , 'value' , 'user_id'
    ];
    
    public function user (){
        $this->belongsTo(User::class, 'user_id');
    }
}

