<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'app',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }
}
