<?php

namespace Acelle\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Http\Controllers\Controller;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        return view('site.templates.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        require_once config('wordpress.1.path') . '/wp-load.php';
        require_once ABSPATH . 'wp-admin/includes/admin.php';
        
        // view
        $view = $request->view ? $request->view : 'list';

        // sort, pagination
        $templates = wp_prepare_themes_for_js();


        return view('site.templates._list_' . $view, [
            'templates' => $templates,
        ]);
    }
}