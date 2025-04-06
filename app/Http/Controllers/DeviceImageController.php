<?php

namespace App\Http\Controllers;

use App\Models\Device;

class DeviceImageController
{
    public function __invoke($id, $key)
    {
        $device = Device::find($id);
        if ($device->key != $key) {
            abort(404);
        }

        $class = $device->currentSchedule();
        $output = (new $class($id))();
        // TODO:Image processing
    }
}
