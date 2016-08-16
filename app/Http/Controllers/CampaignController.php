<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth', ['except' => [
            'open',
            'click',
            'unsubscribe',
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $campaigns = $user->campaigns;

        return view('campaigns.index', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $campaigns = \Mailixa\Model\Campaign::search($request)->paginate($request->per_page);

        return view('campaigns._list', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $campaign = new \Mailixa\Model\Campaign([
                                            'track_open' => true,
                                            'track_click' => true,
                                            'sign_dkim' => true,
                                        ]);

        // authorize
        if (\Gate::denies('create', $campaign)) {
            return $this->noMoreItem();
        }

        $campaign->name = trans('messages.untitled');
        $campaign->user_id = $request->user()->id;
        $campaign->status = \Mailixa\Model\Campaign::STATUS_NEW;
        $campaign->type = $request->type;
        $campaign->save();

        return redirect()->action('CampaignController@recipients', ['uid' => $campaign->uid]);
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
        //
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
        $campaign = \Mailixa\Model\Campaign::findByUid($id);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        if ($campaign->status == 'new') {
            return redirect()->action('CampaignController@edit', ['uid' => $campaign->uid]);
        } else {
            return redirect()->action('CampaignController@overview', ['uid' => $campaign->uid]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($id);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // Check step and redirect
        if ($campaign->step() == 0) {
            return redirect()->action('CampaignController@recipients', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() == 1) {
            return redirect()->action('CampaignController@setup', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() == 2) {
            return redirect()->action('CampaignController@template', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() == 3) {
            return redirect()->action('CampaignController@schedule', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() == 4) {
            return redirect()->action('CampaignController@confirm', ['uid' => $campaign->uid]);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Custom sort items.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort = json_decode($request->sort);
        foreach ($sort as $row) {
            $item = \Mailixa\Model\Campaign::findByUid($row[0]);

            // authorize
            if (\Gate::denies('update', $item)) {
                return $this->notAuthorized();
            }

            $item->custom_order = $row[1];
            $item->save();
        }

        echo trans('messages.lists.custom_order.updated');
    }

    /**
     * Recipients.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function recipients(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        $rules = array(
            'mail_list_uid' => 'required',
        );

        // validate and save posted data
        if ($request->isMethod('post')) {

            // Check validation
            $this->validate($request, \Mailixa\Model\Campaign::$rules);

            // Save campaign
            $campaign->mail_list_id = \Mailixa\Model\MailList::findByUid($request->mail_list_uid)->id;
            if ($request->segment_uid) {
                $campaign->segment_id = \Mailixa\Model\Segment::findByUid($request->segment_uid)->id;
            }
            $campaign->save();

            return redirect()->action('CampaignController@setup', ['uid' => $campaign->uid]);
        }

        return view('campaigns.recipients', [
            'campaign' => $campaign,
            'rules' => $rules,
        ]);
    }

    /**
     * Campaign setup.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function setup(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        $campaign->from_name = !empty($campaign->from_name) ? $campaign->from_name : $campaign->mailList->from_name;
        $campaign->from_email = !empty($campaign->from_email) ? $campaign->from_email : $campaign->mailList->from_email;
        $campaign->subject = !empty($campaign->subject) ? $campaign->subject : $campaign->mailList->default_subject;

        $rules = array(
            'name' => 'required',
            'subject' => 'required',
            'from_email' => 'required|email',
            'from_name' => 'required',
            'reply_to' => 'required|email',
        );

        // Get old post values
        if (null !== $request->old()) {
            $campaign->fill($request->old());
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Check validation
            $this->validate($request, $rules);

            // Save campaign
            $campaign->fill($request->all());
            $campaign->save();

            // Log
            $campaign->log('created', $request->user());

            return redirect()->action('CampaignController@template', ['uid' => $campaign->uid]);
        }

        return view('campaigns.setup', [
            'campaign' => $campaign,
            'rules' => $rules,
        ]);
    }

    /**
     * Template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function template(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // Required tags
        $validate = 'required';
        foreach (\Mailixa\Model\Campaign::$required_tags as $tag) {
            if ($tag['required']) {
                $validate .= '|substring:"'.$tag['name'].'"';
            }
        }

        // if campaign type is not plain text
        if ($campaign->type != 'plain-text') {
            $rules['html'] = $validate;
        }

        // Get old post values
        if (null !== $request->old()) {
            $campaign->fill($request->old());
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Check validation
            $this->validate($request, $rules);

            // Save campaign
            $campaign->fill($request->all());
            // convert html to plain text if plain text is empty
            if (trim($request->plain) == '') {
                $campaign->plain = strip_tags($request->html);
            }
            $campaign->save();

            return redirect()->action('CampaignController@schedule', ['uid' => $campaign->uid]);
        }

        return view('campaigns.template', [
            'campaign' => $campaign,
            'rules' => $rules,
        ]);
    }

    /**
     * Schedule.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        $delivery_date = isset($campaign->run_at) && $campaign->run_at != '0000-00-00 00:00:00' ? \Mailixa\Library\Tool::dateTime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $campaign->run_at))->format('Y-m-d') : \Mailixa\Library\Tool::dateTime(\Carbon\Carbon::now())->format('Y-m-d');
        $delivery_time = isset($campaign->run_at) && $campaign->run_at != '0000-00-00 00:00:00' ? \Mailixa\Library\Tool::dateTime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $campaign->run_at))->format('H:i') : \Mailixa\Library\Tool::dateTime(\Carbon\Carbon::now())->format('H:i');

        $rules = array(
            'delivery_date' => 'required',
            'delivery_time' => 'required',
        );

        // Get old post values
        if (null !== $request->old()) {
            $campaign->fill($request->old());
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Check validation
            // $this->validate($request, $rules);

            // Save campaign
            $campaign->run_at = \Mailixa\Library\Tool::dateTime(\Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->delivery_date.' '.$request->delivery_time))->toDateTimeString();
            $campaign->save();

            return redirect()->action('CampaignController@confirm', ['uid' => $campaign->uid]);
        }

        return view('campaigns.schedule', [
            'campaign' => $campaign,
            'rules' => $rules,
            'delivery_date' => $delivery_date,
            'delivery_time' => $delivery_time,
        ]);
    }

    /**
     * Cofirm.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Save campaign
            $campaign->status = \Mailixa\Model\Campaign::STATUS_READY;
            $campaign->save();

            // Log
            $campaign->log('started', $request->user());

            return redirect()->action('CampaignController@index');
        }

        return view('campaigns.confirm', [
            'campaign' => $campaign,
        ]);
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
        $items = \Mailixa\Model\Campaign::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('delete', $item)) {
                $item->delete();
                // Log
                $item->log('deleted', $request->user());
            }
        }

        // Redirect to my lists page
        echo trans('messages.campaigns.deleted');
    }

    /**
     * Campaign overview.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.overview', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Campaign links.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function links(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.links', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * 24-hour chart.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function chart24h(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $result = [
            'columns' => [],
            'data' => [],
            'bar_names' => [trans('messages.opened'), trans('messages.clicked')],
        ];

        // columns
        for ($i = 23; $i >= 0; --$i) {
            $result['columns'][] = \Carbon\Carbon::now()->subHours($i)->format('h:A');
        }

        // datas
        foreach ($result['bar_names'] as $key => $bar) {
            $data = [];
            if ($key == 0) {
                for ($i = 23; $i >= 0; --$i) {
                    $data[] = $campaign->openUniqCount(
                        \Carbon\Carbon::now()->subHours($i + 1),
                        \Carbon\Carbon::now()->subHours($i)
                    );
                }
            } else {
                for ($i = 23; $i >= 0; --$i) {
                    $data[] = $campaign->clickCount(
                        \Carbon\Carbon::now()->subHours($i + 1),
                        \Carbon\Carbon::now()->subHours($i)
                    );
                }
            }

            $result['data'][] = [
                'name' => $bar,
                'type' => 'line',
                'smooth' => true,
                'data' => $data,
                'itemStyle' => [
                    'normal' => [
                        'areaStyle' => [
                            'type' => 'default',
                        ],
                    ],
                ],
            ];
        }

        return json_encode($result);
    }

    /**
     * 24-hour chart.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function quickView(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns._quick_view', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Select2 campaign.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        $data = ['items' => [], 'more' => true];

        $data['items'][] = ['id' => 0, 'text' => trans('messages.all')];
        foreach (\Mailixa\Model\Campaign::getAll()->get() as $campaign) {
            $data['items'][] = ['id' => $campaign->uid, 'text' => $campaign->name];
        }

        echo json_encode($data);
    }

    /**
     * Tracking when open.
     */
    public function open(Request $request)
    {
        $log = new \Mailixa\Model\OpenLog();
        $log->message_id = $request->message_id;
        $location = \Mailixa\Model\IpLocation::add($_SERVER['REMOTE_ADDR']);
        $log->ip_address = $location->ip_address;
        $log->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $log->save();
    }

    /**
     * Tracking when click link.
     */
    public function click(Request $request)
    {
        $decoded_url = \Mailixa\Library\StringHelper::base64UrlDecode($request->url);
        // redirect base64_decode($url);
        $log = new \Mailixa\Model\ClickLog();
        $log->message_id = $request->message_id;
        $location = \Mailixa\Model\IpLocation::add($_SERVER['REMOTE_ADDR']);
        $log->ip_address = $location->ip_address;
        $log->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $log->url = $decoded_url;
        $log->save();

        return redirect()->away($decoded_url);
    }

    /**
     * Unsubscribe url.
     */
    public function unsubscribe(Request $request)
    {
        $tracking_log = \Mailixa\Model\TrackingLog::where('message_id', '=', $request->message_id)->first();
        $subscriber = $tracking_log->subscriber;

        if ($subscriber->status != 'unsubscribed') {
            // Unsubcribe
            $subscriber->status = 'unsubscribed';
            $subscriber->save();

            // Page content
            $user = $request->user();
            $list = $subscriber->mailList;
            $layout = \Mailixa\Model\Layout::where('alias', 'unsubscribe_success_page')->first();
            $page = \Mailixa\Model\Page::findPage($list, $layout);

            $page->renderContent(null, $subscriber);

            // Unsubscribe log
            $log = new \Mailixa\Model\UnsubscribeLog();
            $log->message_id = $request->message_id;
            $location = \Mailixa\Model\IpLocation::add($_SERVER['REMOTE_ADDR']);
            $log->ip_address = $location->ip_address;
            $log->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $log->save();

            try {
                // Send goodbye email
                $layout = \Mailixa\Model\Layout::where('alias', 'unsubscribe_goodbye_email')->first();
                $send_page = \Mailixa\Model\Page::findPage($list, $layout);
                $send_page->renderContent(null, $subscriber);
                $send_page->sendMail($subscriber, trans('messages.'.$layout->alias.'.real'));
            } catch (\Exception $e) {
            }

            return view('pages.default', [
                'list' => $list,
                'page' => $page,
                'subscriber' => $subscriber,
            ]);
        }
    }

    /**
     * Render user image.
     */
    public function trackingLog(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->trackingLogs;

        return view('campaigns.tracking_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function trackingLogListing(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\TrackingLog::search($request, $campaign)->paginate($request->per_page);

        return view('admin.tracking_logs._list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function bounceLog(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->bounceLogs;

        return view('campaigns.bounce_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function bounceLogListing(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\BounceLog::search($request, $campaign)->paginate($request->per_page);

        return view('admin.bounce_logs._list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function feedbackLog(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->openLogs;

        return view('campaigns.feedback_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function feedbackLogListing(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\FeedbackLog::search($request, $campaign)->paginate($request->per_page);

        return view('admin.feedback_logs._list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function openLog(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->openLogs;

        return view('campaigns.open_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function openLogListing(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\OpenLog::search($request, $campaign)->paginate($request->per_page);

        return view('admin.open_logs._list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function clickLog(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->clickLogs;

        return view('campaigns.click_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function clickLogListing(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\ClickLog::search($request, $campaign)->paginate($request->per_page);

        return view('admin.click_logs._list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function unsubscribeLog(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->unsubscribeLogs;

        return view('campaigns.unsubscribe_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Render user image.
     */
    public function unsubscribeLogListing(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Mailixa\Model\UnsubscribeLog::search($request, $campaign)->paginate($request->per_page);

        return view('admin.unsubscribe_logs._list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Open map.
     */
    public function openMap(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.open_map', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Delete confirm message.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteConfirm(Request $request)
    {
        $lists = \Mailixa\Model\Campaign::whereIn('uid', explode(',', $request->uids));

        return view('campaigns.delete_confirm', [
            'lists' => $lists,
        ]);
    }
    
    /**
     * Pause the specified campaign.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pause(Request $request)
    {        
        $items = \Mailixa\Model\Campaign::whereIn("uid", explode(",", $request->uids));
        
        foreach ($items->get() as $item) {
            if (\Gate::allows('pause', $item)) {
                $item->status = 'paused';
                $item->save();
                
                // Log
                $item->log('paused', $request->user());
            }            
        }

        // Redirect to my lists page
        echo(trans('messages.campaigns.paused'));
    }
    
    /**
     * Pause the specified campaign.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restart(Request $request)
    {        
        $items = \Mailixa\Model\Campaign::whereIn("uid", explode(",", $request->uids));
        
        foreach ($items->get() as $item) {
            if (\Gate::allows('restart', $item)) {
                $item->status = 'ready';
                $item->save();
                
                // Log
                $item->log('restarted', $request->user());
            }            
        }

        // Redirect to my lists page
        echo(trans('messages.campaigns.restarted'));
    }
}
