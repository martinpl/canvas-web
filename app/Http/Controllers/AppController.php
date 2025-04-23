<?php

namespace Canvas\Http\Controllers;

use Canvas\Models\App;

class AppController
{
    public function __invoke($id)
    {
        $app = App::find($id);
        if (! $app) {
            abort(404);
        }

        return view('app', [
            'app' => $app,
            'class' => $app->class(),
        ]);
    }
}
