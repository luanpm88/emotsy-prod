<?php

namespace {{ author_class }}\{{ name_class }}\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller as BaseController;
use Acelle\Model\Plugin;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        // Get the plugin record in the plugin table
        $plugin = Plugin::where('name', '{{ plugin }}')->first();

        //
        return view('{{ name }}::index', [
            'plugin' => $plugin,
        ]);
    }
}
