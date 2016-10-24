<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class SubscriberController extends Controller
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
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $subscribers = $list->subscribers;

        return view('subscribers.index', [
            'subscribers' => $subscribers,
            'list' => $list,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('read', $list)) {
            return;
        }

        $subscribers = \Acelle\Model\Subscriber::search($request)
                                        ->where('mail_list_id', '=', $list->id)
                                        ->groupBy('subscribers.id')
                                        ->paginate($request->per_page);
        $fields = $list->getFields->whereIn('uid', explode(',', $request->columns));

        return view('subscribers._list', [
            'subscribers' => $subscribers,
            'list' => $list,
            'fields' => $fields,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $subscriber = new \Acelle\Model\Subscriber();
        $subscriber->mail_list_id = $list->id;

        // authorize
        if (\Gate::denies('create', $subscriber)) {
            return $this->noMoreItem();
        }

        // Get old post values
        $values = [];
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        return view('subscribers.create', [
            'list' => $list,
            'subscriber' => $subscriber,
            'values' => $values,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $subscriber = new \Acelle\Model\Subscriber();
        $subscriber->mail_list_id = $list->id;
        $subscriber->user_id = $user->id;
        $subscriber->status = 'subscribed';

        // authorize
        if (\Gate::denies('create', $subscriber)) {
            return $this->noMoreItem();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, $subscriber->getRules());

            // Save subscriber
            $subscriber->email = $request->EMAIL;
            $subscriber->save();
            // Update field
            $subscriber->updateFields($request->all());

            // Log
            $subscriber->log('created', $request->user());

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.subscriber.created'));

            return redirect()->action('SubscriberController@index', $list->uid);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // Get old post values
        $values = [];
        foreach ($list->getFields as $key => $field) {
            $values[$field->tag] = $subscriber->getValueByField($field);
        }
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        return view('subscribers.edit', [
            'list' => $list,
            'subscriber' => $subscriber,
            'values' => $values,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $subscriber = $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, $subscriber->getRules());

            // Update field
            $subscriber->updateFields($request->all());

            // Log
            $subscriber->log('updated', $request->user());

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.subscriber.updated'));

            return redirect()->action('SubscriberController@index', $list->uid);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $subscribers = \Acelle\Model\Subscriber::whereIn('uid', explode(',', $request->uids));

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('delete', $subscriber)) {
                return;
            }
        }

        foreach ($subscribers->get() as $subscriber) {
            $subscriber->delete();

            // Log
            $subscriber->log('deleted', $request->user());
        }

        // Redirect to my lists page
        echo trans('messages.subscribers.deleted');
    }

    /**
     * Subscribe subscriber.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $subscribers = \Acelle\Model\Subscriber::whereIn('uid', explode(',', $request->uids));

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('subscribe', $subscriber)) {
                return;
            }
        }

        foreach ($subscribers->get() as $subscriber) {
            $subscriber->status = 'subscribed';
            $subscriber->save();

            // Log
            $subscriber->log('subscribed', $request->user());
        }

        // Redirect to my lists page
        echo trans('messages.subscribers.subscribed');
    }

    /**
     * Unsubscribe subscriber.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $subscribers = \Acelle\Model\Subscriber::whereIn('uid', explode(',', $request->uids));

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('unsubscribe', $subscriber)) {
                return;
            }
        }

        foreach ($subscribers->get() as $subscriber) {
            $subscriber->status = 'unsubscribed';
            $subscriber->save();

            // Log
            $subscriber->log('unsubscribed', $request->user());
        }

        // Redirect to my lists page
        echo trans('messages.subscribers.unsubscribed');
    }

    /**
     * Import from file.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        
        $system_jobs = $request->user()->systemJobs()->where("type", "=", "import-subscriber");
        
        // New job
        $system_job = new \Acelle\Model\SystemJob([
            'user_id' => $request->user()->id,
            'type' => 'import-subscriber',
            'item_id' => $list->id,
            'percent' => 0,
            'status' => 'starting',
        ]);
        
        // authorize
        if (\Gate::denies('importSubscriber', $system_job)) {
            return $this->notAuthorized();
        }

        if ($request->hasFile('file')) {
            $upload_path = storage_path('job/');

            // mkdir if not exist
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            // Save new job
            $system_job->save();
            
            // mkdir if not exist
            $upload_path = $upload_path.$system_job->id."/";
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            // Upload csv
            $filename = 'data.csv';
            $request->file('file')->move($upload_path, $filename);
            
            // Start system job
            $system_job->start();
            
            // Action Log
            $list->log('import_started', $request->user());
            
            echo $system_job->id;
        } else {
            return view('subscribers.import', [
                'list' => $list,
                'system_jobs' => $system_jobs
            ]);
        }
    }

    /**
     * Check import proccessing.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function importProccess(Request $request)
    {
        $system_job = \Acelle\Model\SystemJob::getCurrent("import-subscriber");
        
        // authorize
        if (\Gate::denies('importSubscriber', $system_job)) {
            return $this->notAuthorized();
        }
        
        return response()->json($system_job);
    }
    
    /**
     * Download import log.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadImportLog(Request $request)
    {
        $job = \Acelle\Model\SystemJob::getCurrent("import-subscriber");

        return response()->download(storage_path('job/'.$job->id.'/detail.log'));
    }

    /**
     * Export to csv.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        
        $system_jobs = $request->user()->systemJobs()->where("type", "=", "export-subscriber");
        
        // new system job
        $system_job = new \Acelle\Model\SystemJob([
            'user_id' => $request->user()->id,
            'type' => 'export-subscriber',
            'item_id' => $list->id,
            'percent' => 0,
            'status' => 'starting',
        ]);

        // authorize
        if (\Gate::denies('exportSubscriber', $system_job)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $upload_path = storage_path('job/');

            // mkdir if not exist
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            // Save new job
            $system_job->save();
            
            // mkdir if not exist
            if (!file_exists($upload_path.$system_job->id."/")) {
                mkdir($upload_path.$system_job->id."/", 0777, true);
            }
            
            // Start system job
            $system_job->start();
            
            // Action Log
            $list->log('export_started', $request->user());
            
            echo $system_job->id;
        } else {
            return view('subscribers.export', [
                'list' => $list,
                'system_jobs' => $system_jobs
            ]);
        }
    }

    /**
     * Check export proccessing.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function exportProccess(Request $request)
    {
        $job = \Acelle\Model\SystemJob::getCurrent("export-subscriber");
        return response()->json($job);
    }

    /**
     * Download exported csv file after exporting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadExportedCsv(Request $request)
    {
        $job = \Acelle\Model\SystemJob::getCurrent("export-subscriber");

        return response()->download(storage_path('job/'.$job->id.'/data.csv'));
    }

    /**
     * Copy subscribers to lists.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        $subscribers = \Acelle\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $from_list = $subscribers->first()->mailList;

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('update', $subscriber)) {
                return;
            }
        }

        foreach (\Acelle\Model\Subscriber::whereIn('uid', explode(',', $request->uids))->get() as $subscriber) {
            $subscriber->copy($list, $request->type);
        }

        // Log
        $list->log('copied', $request->user(), [
            'count' => $subscribers->count(),
            'from_uid' => $from_list->uid,
            'to_uid' => $list->uid,
            'from_name' => $from_list->name,
            'to_name' => $list->name,
        ]);

        // Redirect to my lists page
        echo trans('messages.subscribers.copied');
    }

    /**
     * Move subscribers to lists.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request)
    {
        $subscribers = \Acelle\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $from_list = $subscribers->first()->mailList;

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('update', $subscriber)) {
                return;
            }
        }

        foreach (\Acelle\Model\Subscriber::whereIn('uid', explode(',', $request->uids))->get() as $subscriber) {
            $subscriber->move($list, $request->type);
        }

        // Log
        $list->log('moved', $request->user(), [
            'count' => $subscribers->count(),
            'from_uid' => $from_list->uid,
            'to_uid' => $list->uid,
            'from_name' => $from_list->name,
            'to_name' => $list->name,
        ]);

        // Redirect to my lists page
        echo trans('messages.subscribers.moved');
    }
}
