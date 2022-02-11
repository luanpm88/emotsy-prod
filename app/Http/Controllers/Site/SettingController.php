<?php

namespace Acelle\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Acelle\Library\WordpressManager;

class SettingController extends Controller
{
    public function shop(Request $request)
    {        
        return view('site.settings.shop');
    }

    public function sliderRevolution(Request $request)
    {        
        return view('site.settings.sliderRevolution');
    }
}
