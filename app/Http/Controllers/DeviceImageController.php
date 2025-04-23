<?php

namespace Canvas\Http\Controllers;

use Canvas\Image;
use Canvas\Models\Device;

class DeviceImageController
{
    public function __invoke($id, $key)
    {
        $device = Device::find($id);
        if ($device->key != $key) {
            abort(404);
        }

        $class = $device->currentSchedule();
        $output = $class();
        (new Image($output))();
        // TODO: Cleanup
    }
}
