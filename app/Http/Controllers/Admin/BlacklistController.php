<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class BlacklistController extends Controller
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
        if ($request->user()->admin->getPermission('report_blacklist') == 'no') {
            return $this->notAuthorized();
        }

        $request->merge(array("admin_id" => $request->user()->admin->id));
        $blacklists = \Acelle\Model\Blacklist::search($request);

        return view('admin.blacklists.index', [
            'blacklists' => $blacklists,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (!$request->user()->customer->can('read', new \Acelle\Model\Blacklist())) {
            return $this->notAuthorized();
        }

        $request->merge(array("admin_id" => $request->user()->admin->id));
        $blacklists = \Acelle\Model\Blacklist::search($request)->paginate($request->per_page);

        return view('admin.blacklists._list', [
            'blacklists' => $blacklists,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items = \Acelle\Model\Blacklist::whereIn('email', explode(',', $request->emails));

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->admin->getPermission('report_blacklist') == 'no') {
                return;
            }
        }

        foreach ($items->get() as $item) {
            $item->delete();
        }

        // Redirect to my lists page
        echo trans('messages.blacklists.deleted');
    }

    /**
     * Start import process.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $admin = $request->user()->admin;

        if ($request->isMethod('post')) {
            // authorize
            if (!$admin->can('import', new \Acelle\Model\Blacklist())) {
                return $this->notAuthorized();
            }

            if ($request->hasFile('file')) {
                // Start system job
                $job = new \Acelle\Jobs\ImportBlacklistJob($request->file('file')->path(), NULL, $admin);
                $this->dispatch($job);
            } else {
                // @note: use try/catch instead
                echo "max_file_upload";
            }
        }

        // Get current job
        $system_job = $admin->getLastActiveImportBlacklistJob();

        return view('admin.blacklists.import', [
            'system_job' => $system_job
        ]);
    }

    /**
     * Check import proccessing.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function importProcess(Request $request)
    {
        $customer = $request->user()->customer;
        $system_job = \Acelle\Model\SystemJob::find($request->system_job_id);

        // authorize
        if (!$customer->can('read', new \Acelle\Model\Blacklist())) {
            return $this->notAuthorized();
        }

        return view('admin.blacklists.import_process', [
            'system_job' => $system_job
        ]);
    }
}
