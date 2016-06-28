<?php

namespace Mailixa\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.welcome');
    }
}
