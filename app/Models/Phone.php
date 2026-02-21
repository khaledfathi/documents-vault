<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'phone', 'user_id'
    ];

    public function user (){
        $this->belongsTo(User::class , 'user_id');
    }
}
