<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'permission'
    ];

    public function groupPermissions(){
        $this->hasMany(Permission::class, 'permission_id');
    }
}
