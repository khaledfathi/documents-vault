<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [ 'name' ];
    public function users (){
        $this->hasMany(User::class, 'group_id');
    }
    public function groupPermissions (){
        $this->hasMany(GroupPermission::class, 'group_id');
    }
}
