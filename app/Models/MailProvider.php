<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailProvider extends Model
{
    protected $fillable = [
        'name',
        'driver',
        'host',
        'port',
        'user_name',
        'password',
        'from_name',
        'is_active'
    ];

    public static function active(){
        return static::where('is_active',1)->first();
    }
}
