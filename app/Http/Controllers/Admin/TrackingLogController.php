<?php

namespace Mailixa\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mailixa\Http\Controllers\Controller;

class TrackingLogController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->getOption('backend', 'report_tracking_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\TrackingLog::getAll();

        return view('admin.tracking_logs.index', [
            'items' => $items,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if ($request->user()->getOption('backend', 'report_tracking_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\TrackingLog::search($request)->paginate($request->per_page);

        return view('admin.tracking_logs._list', [
            'items' => $items,
        ]);
    }
}
