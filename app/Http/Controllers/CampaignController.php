<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class CampaignController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = \Mailixa\Model\Campaign::getAll();
        return view('campaigns.index', [
            'campaigns' => $campaigns
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
            'campaigns' => $campaigns
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
                                            'track_fbl' => true,
                                            'sign_dkim' => true,
                                        ]);
        $campaign->name = trans('messages.untitled');
        $campaign->user_id = $request->user()->id;
        $campaign->status = \Mailixa\Model\Campaign::STATUS_NEW;
        $campaign->type = \Mailixa\Model\Campaign::TYPE_REGULAR;
        $campaign->save();
        
        return redirect()->action('CampaignController@recipients', ['uid' => $campaign->uid]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($id);

        // Check step and redirect
        if($campaign->step() == 0) {
            return redirect()->action('CampaignController@recipients', ['uid' => $campaign->uid]);
        } else if ($campaign->step() == 1) {
            return redirect()->action('CampaignController@setup', ['uid' => $campaign->uid]);
        } else if ($campaign->step() == 2) {
            return redirect()->action('CampaignController@template', ['uid' => $campaign->uid]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Custom sort items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort = json_decode($request->sort);
        foreach($sort as $row) {
            $item = \Mailixa\Model\Campaign::findByUid($row[0]);
            $item->custom_order = $row[1];
            $item->save();            
        }
        
        echo trans('messages.lists.custom_order.updated');
    }
    
    /**
     * Recipients.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recipients(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);
        
        $rules = array(
            'mail_list_uid' => 'required',
        );
            
        // validate and save posted data
        if ($request->isMethod('post'))
        {
            
            // Check validation
            $this->validate($request, \Mailixa\Model\Campaign::$rules);
            
            // Save campaign
            $campaign->mail_list_id = \Mailixa\Model\MailList::findByUid($request->mail_list_uid)->id;
            if($request->segment_uid) {
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setup(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);
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
        if(null !== $request->old()) {
            $campaign->fill($request->old());
        }
            
        // validate and save posted data
        if ($request->isMethod('post'))
        {            
            // Check validation
            $this->validate($request, $rules);
            
            // Save campaign
            $campaign->fill($request->all());
            $campaign->save();
            
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function template(Request $request)
    {
        $campaign = \Mailixa\Model\Campaign::findByUid($request->uid);
        
        // Required tags
        $validate = "required";
        foreach(\Mailixa\Model\Campaign::$template_tags as $tag) {            
            if($tag['required']) {
                $validate .= '|substring:"'.$tag["name"].'"';
            }
        }
        
        $rules = array(
            'html' => $validate,
            'plain' => 'required',
        );
        
        // Get old post values
        if(null !== $request->old()) {
            $campaign->fill($request->old());
        }
            
        // validate and save posted data
        if ($request->isMethod('post'))
        {            
            // Check validation
            $this->validate($request, $rules);
            
            // Save campaign
            $campaign->fill($request->all());
            $campaign->save();
            
            return redirect()->action('CampaignController@template', ['uid' => $campaign->uid]);
        }
        
        return view('campaigns.template', [
            'campaign' => $campaign,
            'rules' => $rules,
        ]);
    }
}
