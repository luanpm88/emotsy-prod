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
        
        return view('pages.preview_' . $page->layout->type, [
            'list' => $list,
            'page' => $page,
        ]);
    }
    
    /**
     * Sign up form page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sign_up_form(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "sign_up_form")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);

        // Get old post values
        $values = [];
        if(null !== $request->old()) {
            foreach($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace("[]", "", $key)] = implode(",", $value);
                } else {
                    $values[$key] = $value;
                }                
            }
        }
        
        $page->renderContent($values);
        
        // Create subscriber
        if ($request->isMethod('post'))
        {
            $subscriber = new \Mailixa\Model\Subscriber($request->all());
            $subscriber->mail_list_id = $list->id;
            $subscriber->user_id = $user->id;
            $subscriber->status = 'unconfirmed';
            $subscriber->from = 'web';
            
            // Validation
            $this->validate($request, $subscriber->getRules());
            
            $subscriber->email = $request->EMAIL;
            $subscriber->ip = $request->ip();            
            $subscriber->save();
            // Update field
            $subscriber->updateFields($request->all());
            
            // SEND EMAIL
            $layout = \Mailixa\Model\Layout::where("alias", "sign_up_confirmation_email")->first();
            $send_page = \Mailixa\Model\Page::findPage($list, $layout);
            $send_page->renderContent(null, $subscriber);    
            $send_page->sendMail($subscriber, trans('messages.' . $layout->alias . '.real'));
            
            return redirect()->action('PageController@sign_up_thankyou_page', $list->uid);
        }       
        
        return view('pages.form', [
            'list' => $list,
            'page' => $page,
            'values' => $values,
        ]);
    }
    
    /**
     * Sign up thank you page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sign_up_thankyou_page(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "sign_up_thankyou_page")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        
        
        $page->renderContent();
        
        
        return view('pages.default', [
            'list' => $list,
            'page' => $page,
        ]);
    }
    
    /**
     * Sign up confirmation thank you page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sign_up_confirmation_thankyou(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "sign_up_confirmation_thankyou")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);
        
        $page->renderContent(null, $subscriber);
        
        if($subscriber->getSecurityToken("subscribe-confirm") == $request->code && $subscriber->status == "unconfirmed") {
            $subscriber->status = "subscribed";
            $subscriber->save();
            
            // Send welcome email
            // SEND EMAIL
            $layout = \Mailixa\Model\Layout::where("alias", "sign_up_welcome_email")->first();
            $send_page = \Mailixa\Model\Page::findPage($list, $layout);
            $send_page->renderContent(null, $subscriber);    
            $send_page->sendMail($subscriber, trans('messages.' . $layout->alias . '.real'));
            
        }
        
        return view('pages.default', [
            'list' => $list,
            'page' => $page,
        ]);
    }
    
    /**
     * Unsibscribe form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe_form(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "unsubscribe_form")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);
        
        $page->renderContent(null, $subscriber);
        
        if ($request->isMethod('post')) {
            if($subscriber->getSecurityToken("unsubscribe") == $request->code && $subscriber->status == "subscribed") {
                // Validation
                $this->validate($request, ['EMAIL' => 'required|email|exists:subscribers,email']);
                
                $subscriber->status = "unsubscribed";
                $subscriber->save();
                
                // Send welcome email
                // SEND EMAIL
                $layout = \Mailixa\Model\Layout::where("alias", "unsubscribe_goodbye_email")->first();
                $send_page = \Mailixa\Model\Page::findPage($list, $layout);
                $send_page->renderContent(null, $subscriber);    
                $send_page->sendMail($subscriber, trans('messages.' . $layout->alias . '.real'));
                
                return redirect()->action('PageController@unsubscribe_success_page', ['list_uid' => $list->uid, "uid" => $subscriber->uid]);
            }     
        }
        
        return view('pages.form', [
            'list' => $list,
            'page' => $page,
        ]);
    }
    
    /**
     * Unsibscribe form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe_success_page(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "unsubscribe_success_page")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);
        
        $page->renderContent(null, $subscriber);        
        
        return view('pages.default', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber
        ]);
    }
    
    /**
     * Update profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile_update_form(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "profile_update_form")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);
        
        // Get old post values
        $values = [];
        if(null !== $request->old()) {
            foreach($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace("[]", "", $key)] = implode(",", $value);
                } else {
                    $values[$key] = $value;
                }                
            }
        }

        $page->renderContent($values, $subscriber);        
        
        if ($request->isMethod('post')) {
            if($subscriber->getSecurityToken("update-profile") == $request->code) {
                $rules = $subscriber->getRules();
                $rules['EMAIL'] .= '|in:' . $subscriber->email;
                // Validation
                $this->validate($request, $rules);
                
                // Update field
                $subscriber->updateFields($request->all());
                
                return redirect()->action('PageController@profile_update_success_page', ['list_uid' => $list->uid, "uid" => $subscriber->uid]);
            }     
        }
        
        return view('pages.form', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber
        ]);
    }
    
    /**
     * Update profile success.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile_update_success_page(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "profile_update_success_page")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);
        
        $page->renderContent(null, $subscriber);
        
        return view('pages.default', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber
        ]);
    }
    
    /**
     * Send update profile request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile_update_email_sent(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $layout = \Mailixa\Model\Layout::where("alias", "profile_update_email_sent")->first();
        $page = \Mailixa\Model\Page::findPage($list, $layout);
        $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);
        
        $page->renderContent(null, $subscriber);
        
        // SEND EMAIL
        $layout = \Mailixa\Model\Layout::where("alias", "profile_update_email")->first();
        $send_page = \Mailixa\Model\Page::findPage($list, $layout);
        $send_page->renderContent(null, $subscriber);    
        $send_page->sendMail($subscriber, trans('messages.' . $layout->alias . '.real'));
        
        return view('pages.default', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber
        ]);
    }
}
