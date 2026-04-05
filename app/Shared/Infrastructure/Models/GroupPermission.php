<?php

namespace App\Shared\Infrastructure\Models; 

use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
    protected $table = 'group_permissions';
    protected $fillable = [
        'group_id', 
        'permission_id'
    ];
   
    public function group (){
        return $this->belongsTo(Group::class , 'group_id');
    }
    public function permission (){
        return $this->belongsTo(Permission::class , 'permission_id');
    }
}
