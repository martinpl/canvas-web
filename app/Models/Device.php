<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($device) {
            $device->key = Str::random(16);
        });
    }

    public function infoUrl()
    {
        return route('device.info', ['id' => $this->id, 'key' => $this->key]);
    }

    public function imageUrl()
    {
        return route('device.image', ['id' => $this->id, 'key' => $this->key]);
    }
}
