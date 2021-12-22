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
        $wordpress = new \Acelle\Library\WordpressManager();

        // view
        $view = $request->view ? $request->view : 'list';

        // wordpress themes
        $templates = $wordpress->getTemplates();

        return view('site.templates._list_' . $view, [
            'templates' => $templates,
        ]);
    }

    public function activate(Request $request, $id)
    {
        $wordpress = new \Acelle\Library\WordpressManager();

        $wordpress->activateTheme($id);

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.site.template_changed'),
        ]);
    }
}
