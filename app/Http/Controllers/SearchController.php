<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Exception;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        return view('search.index', [
            'keyword' => $request->keyword,
        ]);
    }

    public function general(Request $request)
    {
        sleep(1);
        // var $results = [

        // ];
        return view('search.general', [
            'keyword' => $request->keyword,
        ]);
    }
    
    public function campaigns(Request $request)
    {
        sleep(1);
        // var $results = [

        // ];
        return view('search.campaigns', [
            'keyword' => $request->keyword,
        ]);
    }
}
