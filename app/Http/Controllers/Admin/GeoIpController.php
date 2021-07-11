<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class GeoIpController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.geoip.index');
    }
}
