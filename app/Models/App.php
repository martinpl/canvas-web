<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function class()
    {
        $className = Str::studly($this->app);
        $class = "Apps\\{$className}\\{$className}";

        return new $class($this->id);
    }
}
