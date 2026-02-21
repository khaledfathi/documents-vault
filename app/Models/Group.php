<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name' , 'description'
    ];

    public function users (){
        $this->hasMany(User::class, 'group_id');
    }
    public function groupPermissions (){
        $this->hasMany(GroupPermission::class, 'group_id');
    }
}
