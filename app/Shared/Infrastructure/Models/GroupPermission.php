<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
    protected $fillable = [
        'group_id', 
        'permission_id'
    ];
   
    public function group (){
        $this->belongsTo(Group::class , 'group_id');
    }
    public function permission (){
        $this->belongsTo(Permission::class , 'permission_id');
    }
}
