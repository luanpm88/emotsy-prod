<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
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
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $subscribers = \Mailixa\Model\Subscriber::search($request)->paginate($request->per_page);
        
        return view('subscribers._list', [
            'subscribers' => $subscribers,
            'list' => $list,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $subscriber = new \Mailixa\Model\Subscriber;        
        
        // authorize
        if (\Gate::denies('create', $subscriber)) {
            return $this->notAuthorized();
        }
        
        // Get old post values
        $values = [];
        if(null !== $request->old()) {
            foreach($request->old() as $key => $value) {
                $values[$key] = $value;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $subscriber = new \Mailixa\Model\Subscriber;
        $subscriber->mail_list_id = $list->id;
        $subscriber->user_id = $user->id;
        $subscriber->status = 'subscribed';
        
        // authorize
        if (\Gate::denies('create', $subscriber)) {
            return $this->notAuthorized();
        }
        
        // validate and save posted data
        if ($request->isMethod('post'))
        {
            $this->validate($request, $subscriber->getRules());
            
            // Save subscriber
            $subscriber->email = $request->EMAIL;
            $subscriber->save();
            // Update field
            $subscriber->updateFields($request->all());
            
            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.subscriber.created'));
            return redirect()->action('SubscriberController@index', $list->uid);
        }
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
    public function edit(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);        
        
        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }
        
        // Get old post values
        $values = [];
        foreach($list->getFields as $key => $field) {
            $values[$field->tag] = $subscriber->getValueByField($field);
        }
        if(null !== $request->old()) {
            foreach($request->old() as $key => $value) {
                $values[$key] = $value;
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $subscriber = $subscriber = \Mailixa\Model\Subscriber::findByUid($request->uid);
        
        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }
        
        // validate and save posted data
        if ($request->isMethod('patch'))
        {
            $this->validate($request, $subscriber->getRules());
            
            // Update field
            $subscriber->updateFields($request->all());
            
            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.subscriber.updated'));
            return redirect()->action('SubscriberController@index', $list->uid);
        }
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {        
        $subscribers = \Mailixa\Model\Subscriber::whereIn("uid", explode(",", $request->uids));
        
        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('delete', $subscriber)) {
                return $this->notAuthorized();
            }
        }
        
        foreach ($subscribers->get() as $subscriber) {
            $subscriber->delete();
        }

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.subscribers.deleted'));
        return redirect()->action('SubscriberController@index', $request->list_uid);
    }
    
    /**
     * Subscribe item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {        
        $subscribers = \Mailixa\Model\Subscriber::whereIn("uid", explode(",", $request->uids));
        
        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('subscribe', $subscriber)) {
                return $this->notAuthorized();
            }
        }
        
        foreach ($subscribers->get() as $subscriber) {
            $subscriber->status = "subscribed";
            $subscriber->save();
        }

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.subscribers.subscribed'));
        return redirect()->action('SubscriberController@index', $request->list_uid);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {        
        $subscribers = \Mailixa\Model\Subscriber::whereIn("uid", explode(",", $request->uids));
        
        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::denies('unsubscribe', $subscriber)) {
                return $this->notAuthorized();
            }
        }
        
        foreach ($subscribers->get() as $subscriber) {
            $subscriber->status = "unsubscribed";
            $subscriber->save();
        }

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.subscribers.unsubscribed'));
        return redirect()->action('SubscriberController@index', $request->list_uid);
    }
}
