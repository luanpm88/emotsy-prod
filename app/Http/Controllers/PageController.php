<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class PageController extends Controller
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
     * Update list page content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }
        
        $layout = \Mailixa\Model\Layout::where("alias", $request->alias)->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        
        // storing
        if ($request->isMethod('post')) {
            $validate = "required";
            foreach ($layout->tags() as $tag) {
                if ($tag["required"]) {
                    $validate .= "|substring:".$tag["name"];
                }
            }
            
            $rules = array(
                'content' => $validate,
            );
            
            $this->validate($request, $rules);

            // save
            $page->fill($request->all());
            $page->save();
            
            $request->session()->flash('alert-success', trans('messages.page.updated'));
            return redirect()->action('PageController@update', array("list_uid" => $list->uid, "alias" => $layout->alias ));
        }
        
        // return back
        $page->fill($request->old());
        
        return view('pages.update', [
            'list' => $list,
            'page' => $page,
            'layout' => $layout,
        ]);
    }
    
    /**
     * Update subscribe form page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $list = MailList::find($request->list_id);
        $list_page_type = \Mailixa\Web\ListPageType::where("slug", "subscribe-form")->first();
        $list_page = ListPage::findPage($list, $list_page_type);
        
        $list_page->renderContent($request);
        
        
        // Create subscriber
        // validate and save posted data
        if ($request->isMethod('post')) {
            // Update email for subscriber + Validation
            $request->offsetSet('email', $request->all()[\Mailixa\Web\ListField::$main_tag]);
            $request->offsetSet('list_id', $list->list_id);
            $request->offsetSet('status', 'unconfirmed');
            
            $rules = array(
                        'list_id' => 'required',
                        'status' => 'required',
                        'email' => 'required|email|unique:list_subscriber',
                    );
            $message = array();
            foreach ($list->list_fields as $lf) {
                if ($lf->required == 'yes' && $lf->visibility == 'visible' && $lf->tag != \Mailixa\Web\ListField::$main_tag) {
                    $rules[$lf->tag] = 'required';
                    $message[$lf->tag . ".required"] = 'The '.$lf->label.' field is required.';
                }
            }
        
            $this->validate($request, $rules, $message);
            
            // save subscribers
            $subscriber = new \Mailixa\Web\Subscriber($request->all());
            $subscriber->ip_address = $request->ip();
            $subscriber->source = 'web';
            $subscriber->save();
            // save fields
            $subscriber->update_fields($request);
            
            // SEND EMAIL
            $send_page_type = \Mailixa\Web\ListPageType::where("slug", "subscribe-confirm-email")->first();
            $send_page = ListPage::findPage($list, $send_page_type);
            $send_page->renderContent($request, $subscriber);
            $send_page->sendMail($subscriber, $send_page_type->name);
            
            $request->session()->flash('alert-info', trans('messages.subscribeConfirmation_email_sent'));
            return redirect()->action('ListPageController@subscribePending', $list->list_id);
        }
        
        return view('list_pages.front.subscribe', [
            'list' => $list,
            'list_page' => $list_page,
        ]);
    }
    
    /**
     * Update subscribe pending page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribePending(Request $request)
    {
        $list = MailList::find($request->list_id);
        $list_page_type = \Mailixa\Web\ListPageType::where("slug", "subscribe-pending")->first();
        $list_page = ListPage::findPage($list, $list_page_type);
        
        $list_page->renderContent($request);
        
        return view('list_pages.front.subscribe-pending', [
            'list' => $list,
            'list_page' => $list_page,
        ]);
    }
    
    /**
     * Update subscribe confirm page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribeConfirm(Request $request)
    {
        $list = MailList::find($request->list_id);
        $list_page_type = \Mailixa\Web\ListPageType::where("slug", "subscribe-confirm")->first();
        $list_page = ListPage::findPage($list, $list_page_type);
        
        // Subscriber
        $subscriber = \Mailixa\Web\Subscriber::where("email", $request->email)->first();
        $subscriber->get_code("subscribe-confirm");
        
        // Render page content
        $list_page->renderContent($request, $subscriber);
        
        if ($subscriber->get_code("subscribe-confirm") == $request->code && $subscriber->status == "unconfirmed") {
            $subscriber->status = "confirmed";
            $subscriber->save();
            
            // Send welcome email
            // SEND EMAIL
            $send_page_type = \Mailixa\Web\ListPageType::where("slug", "welcome-email")->first();
            $send_page = ListPage::findPage($list, $send_page_type);
            $send_page->renderContent($request, $subscriber);
            $send_page->sendMail($subscriber, $send_page_type->name);
            
            $request->session()->flash('alert-success', 'You have successfully subscribed!');
            
            return view('list_pages.front.subscribe-confirm', [
                'list' => $list,
                'list_page' => $list_page,
            ]);
        } else {
            return redirect()->action('ListPageController@subscribe', $list->list_id);
        }
    }
    
    /**
     * Update update profile page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $list = MailList::find($request->list_id);
        $list_page_type = \Mailixa\Web\ListPageType::where("slug", "update-profile")->first();
        $list_page = ListPage::findPage($list, $list_page_type);
        $subscriber = \Mailixa\Web\Subscriber::where('email', $request->email)->first();
        $list_page->renderContent($request, $subscriber);
        
        
        // Update subscriber
        // validate and save posted data
        if ($subscriber->email == $request->email && $subscriber->get_code("update-profile") == $request->code) {
            if ($request->isMethod('post')) {
                // Update email for subscriber + Validation
                $request->offsetSet('email', $request->all()[\Mailixa\Web\ListField::$main_tag]);
                $request->offsetSet('list_id', $list->list_id);
                
                $rules = array(
                            'list_id' => 'required',
                            'email' => 'required|email|exists:list_subscriber',
                        );
               
                $message = array();
                foreach ($list->list_fields as $lf) {
                    if ($lf->required == 'yes' && $lf->visibility == 'visible' && $lf->tag != \Mailixa\Web\ListField::$main_tag) {
                        $rules[$lf->tag] = 'required';
                        $message[$lf->tag . ".required"] = 'The '.$lf->label.' field is required.';
                    }
                }
                
                $this->validate($request, $rules, $message);
                
                // save subscribers
                $subscriber = \Mailixa\Web\Subscriber::where('email', $request->all()[\Mailixa\Web\ListField::$main_tag])->first();
                $subscriber->fill($request->except(array('status')));
                $subscriber->save();
                // save fields
                $subscriber->update_fields($request);
                
                $request->session()->flash('alert-success', trans('messages.list_page.profile_updated'));
                //return redirect()->action('ListPageController@updateProfile', $list->id);
            }
        } else {
            $request->session()->flash('alert-danger', 'Invalid security code!');
        }
        
        return view('list_pages.front.update-profile', [
            'list' => $list,
            'list_page' => $list_page,
            'subscriber' => $subscriber,
            'code' => $request->code,
            'email' => $request->email,
        ]);
    }
    
    /**
     * Update unsubscribe page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $list = MailList::find($request->list_id);
        $list_page_type = \Mailixa\Web\ListPageType::where("slug", "unsubscribe-form")->first();
        $list_page = ListPage::findPage($list, $list_page_type);
        
        $list_page->renderContent($request);
        
        
        if ($request->isMethod('post')) {
            // Update email for subscriber + Validation
            $request->offsetSet('email', $request->all()[\Mailixa\Web\ListField::$main_tag]);
            
            $rules = array(
                        'email' => 'required|email|exists:list_subscriber',
                    );
           
            $this->validate($request, $rules);
            
            // unsubscribe
            $subscriber = \Mailixa\Web\Subscriber::where('email', $request->all()[\Mailixa\Web\ListField::$main_tag])->first();
            
            // SEND EMAIL
            $send_page_type = \Mailixa\Web\ListPageType::where("slug", "unsubscribe-confirm-email")->first();
            $send_page = ListPage::findPage($list, $send_page_type);
            $send_page->renderContent($request, $subscriber);
            $send_page->sendMail($subscriber, $send_page_type->name);
            
            
            $request->session()->flash('alert-info', trans('messages.list_page.unsubscribe_email_sent'));
            return redirect()->action('ListPageController@unsubscribe', $list->list_id);
        }
        
        
        return view('list_pages.front.unsubscribe', [
            'list' => $list,
            'list_page' => $list_page,
            'email' => $request->email,
        ]);
    }
    
    /**
     * Update unsubscribe confirm page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribeConfirm(Request $request)
    {
        $list = MailList::find($request->list_id);
        $list_page_type = \Mailixa\Web\ListPageType::where("slug", "unsubscribe-confirm")->first();
        $list_page = ListPage::findPage($list, $list_page_type);
        
        $list_page->renderContent($request);
        
        // Subscriber
        $subscriber = \Mailixa\Web\Subscriber::where("email", $request->email)->first();
        
        if ($subscriber->get_code("unsubscribe-confirm") == $request->code && $subscriber->status != "unsubscribed") {
            $subscriber->status = "unsubscribed";
            $subscriber->save();
            
            $request->session()->flash('alert-success', trans('messages.list_page.unsubscribed'));
            
            return view('list_pages.front.unsubscribe-confirm', [
                'list' => $list,
                'list_page' => $list_page,
            ]);
        } else {
            return redirect()->action('ListPageController@subscribe', $list->list_id);
        }
        
        return view('list_pages.front.unsubscribe-confirm', [
            'list' => $list,
            'list_page' => $list_page,
        ]);
    }
    
    /**
     * Update unsubscribe confirmed page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribeConfirmed(Request $request)
    {
        $list = MailList::where('list_uid', $request->list_uid)->first();
        $list_page_type = \Mailixa\Web\ListPageType::where("slug", "unsubscribe-confirm")->first();
        $list_page = ListPage::findPage($list, $list_page_type);
        
        $list_page->renderContent($request);
        
        // Subscriber
        $subscriber = \Mailixa\Web\Subscriber::where("subscriber_uid", $request->subscriber_uid)->first();
        $campaign = \Mailixa\Web\Campaign::where("campaign_uid", $request->campaign_uid)->first();
        
        if ($subscriber->status != "unsubscribed") {
            $subscriber->status = "unsubscribed";
            $subscriber->save();
            
            //// Add unsubscribe track
            //$location = \Mailixa\Web\IpLocation::add($_SERVER['REMOTE_ADDR']);
            //$track = new \Mailixa\Web\CampaignTrackUnsubscribe();
            //$track->campaign_id = $campaign->campaign_id;
            //$track->subscriber_id = $subscriber->subscriber_id;
            //$track->location_id = $location->location_id;
            //$track->ip_address = $ip;
            //$track->user_agent = $_SERVER['HTTP_USER_AGENT'];
            //$track->save();

            
            $request->session()->flash('alert-success', trans('messages.list_page.unsubscribed'));
            
            return view('list_pages.front.unsubscribe-confirm', [
                'list' => $list,
                'list_page' => $list_page,
            ]);
        } else {
            return redirect()->action('ListPageController@subscribe', $list->list_id);
        }
        
        return view('list_pages.front.unsubscribe-confirm', [
            'list' => $list,
            'list_page' => $list_page,
        ]);
    }
    
    /**
     * Preview page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }
        
        $layout = \Mailixa\Model\Layout::where("alias", $request->alias)->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        $page->content = $request->content;
        
        // render content
        $page->renderContent();
        
        return view('pages.preview', [
            'list' => $list,
            'page' => $page,
        ]);
    }
}
