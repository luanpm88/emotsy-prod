<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class PagetemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('pagetemplates.index');
    }
    
    

}
