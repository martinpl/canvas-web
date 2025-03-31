<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    protected function casts(): array
    {
        return [
            'schedule' => 'array',
        ];
    }
}
