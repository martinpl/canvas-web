<?php

namespace Canvas\Http\Controllers;

use Canvas\Models\Device;
use Canvas\Models\Log;

class DeviceInfoController
{
    public function __invoke($id, $key)
    {
        $device = Device::find($id);
        if ($device->key != $key) {
            abort(404);
        }

        if (request()->input('batteryPercent')) {
            $device->battery = request()->input('batteryPercent');
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
