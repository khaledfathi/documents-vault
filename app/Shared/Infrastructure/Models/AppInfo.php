<?php

namespace App\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class AppInfo extends Model
{
    protected $table = 'app_infos';
    protected $fillable = ['key', 'value'];

}
