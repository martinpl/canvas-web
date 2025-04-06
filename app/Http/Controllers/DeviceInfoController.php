<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Log;

class DeviceInfoController
{
    public function __invoke($id, $key)
    {
        $device = Device::find($id);
        if ($device->key != $key) {
            abort(404);
        }

        if (request()->input('battery')) {
            $device->battery = request()->input('battery');
            $device->save();
        }

        Log::create([
            'type' => 'http',
            'message' => 'Device Info '.request()->ip(),
            'device_id' => $device->id,
        ]);

        // TODO: Refresh time
        return response()->json([
            'img' => $device->imageUrl(),
            'refresh' => '1440',
        ]);
    }
}
