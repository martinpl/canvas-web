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

    public function refreshKey()
    {
        $this->key = Str::random(16);
        $this->save();
    }

    // TODO:Add schedule matching, better return?
    public function currentSchedule()
    {
        if (empty($this->schedule[0]['app'])) {
            return;
        }

        $app = App::find($this->schedule[0]['app']);

        return $app->class();
    }
}
