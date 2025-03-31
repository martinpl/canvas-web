<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'type',
        'message',
        'device_id',
    ];
}
