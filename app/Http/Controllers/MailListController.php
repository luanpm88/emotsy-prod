<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class MailListController extends Controller
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
        $lists = \Mailixa\Model\MailList::getAll();
        return view('lists.index', [
            'lists' => $lists
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $lists = \Mailixa\Model\MailList::search($request)->paginate($request->per_page);
        
        return view('lists._list', [
            'lists' => $lists
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Generate info
        $user = $request->user();
        $list = new \Mailixa\Model\MailList();
        $list->contact = new \Mailixa\Model\Contact();
        if (is_object($user->contact)) {
            $list->contact->fill($user->contact->toArray());
            $list->send_to = $user->contact->email;
        } else {
            $list->send_to = $user->email;
        }
        
        
        // authorize
        if (\Gate::denies('create', $list)) {
            return $this->noMoreItem();
        }
        
        // Get old post values
        if(null !== $request->old()) {
            $list->fill($request->old());
        }
        if(isset($request->old()["contact"])) {
            $list->contact->fill($request->old()["contact"]);
        }
        
        return view('lists.create', [
            'list' => $list,
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
        // Generate info
        $user = $request->user();        
        $list = new \Mailixa\Model\MailList();
        
        // authorize
        if (\Gate::denies('create', $list)) {
            return $this->noMoreItem();
        }
        
        // validate and save posted data
        if ($request->isMethod('post'))
        {
            $this->validate($request, \Mailixa\Model\MailList::$rules);
            
            // Save contact
            $contact = \Mailixa\Model\Contact::create($request->all()["contact"]);
            $list->fill($request->all());
            $list->user_id = $user->id;
            $list->contact_id = $contact->id;
            $list->save();
            
            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.list.created'));
            return redirect()->action('MailListController@index');

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
    public function edit(Request $request, $uid)
    {
        // Generate info
        $user = $request->user();
        $list = \Mailixa\Model\MailList::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }
        
        // Get old post values
        if(null !== $request->old()) {
            $list->fill($request->old());
        }
        if(isset($request->old()["contact"])) {
            $list->contact->fill($request->old()["contact"]);
        }
        
        return view('lists.edit', [
            'list' => $list,
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
        // Generate info
        $user = $request->user();        
        $list = \Mailixa\Model\MailList::findByUid($request->uid);
        
        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }
        
        // validate and save posted data
        if ($request->isMethod('patch'))
        {
            $this->validate($request, \Mailixa\Model\MailList::$rules);
            
            // Save contact
            $list->contact->fill($request->all()["contact"]);
            $list->contact->save();
            $list->fill($request->all());
            $list->save();
            
            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.list.updated'));
            return redirect()->action('MailListController@index');

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
     * Custom sort items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort = json_decode($request->sort);
        foreach($sort as $row) {
            $list = \Mailixa\Model\MailList::findByUid($row[0]);
            
            // authorize
            if (\Gate::denies('update', $list)) {
                return $this->notAuthorized();
            }
            
            $list->custom_order = $row[1];
            $list->save();            
        }
        
        echo trans('messages.lists.custom_order.updated');
    }
    
    /**
     * Delete confirm message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteConfirm(Request $request)
    {
        $lists = \Mailixa\Model\MailList::whereIn("uid", explode(",", $request->uids));        

        return view('lists.delete_confirm', [
            'lists' => $lists,
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {        
        $lists = \Mailixa\Model\MailList::whereIn("uid", explode(",", $request->uids));
        
        foreach ($lists->get() as $item) {
            // authorize
            if (\Gate::denies('delete', $item)) {
                return;
            }
        }
        
        foreach ($lists->get() as $item) {
            $item->delete();
        }

        // Redirect to my lists page
        echo(trans('messages.lists.deleted'));
    }
    
    /**
     * List overview.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->uid); 

        return view('lists.overview', [
            'list' => $list,
        ]);
    }
    
    /**
     * List growth chart content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listGrowthChart(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->uid);
        
        if(is_object($list)) {
            $list_id = $list->id;
        } else {
            $list_id = null;
        }
        
        $result = [
            "columns" => [],
            "data" => [],
            "bar_names" => [trans('messages.subscriber_growth')],
        ];
        
        // columns
        for($i = 2; $i >= 0; $i--) {
            $result["columns"][] = \Carbon\Carbon::now()->subMonthsNoOverflow($i)->format('m/Y');
        }
        
        // datas
        foreach($result["bar_names"] as $bar) {
            $data = [];
            for($i = 2; $i >= 0; $i--) {
                $data[] = \Mailixa\Model\User::subscribersCountByTime(
                    \Carbon\Carbon::now()->subMonthsNoOverflow($i)->startOfMonth(),
                    \Carbon\Carbon::now()->subMonthsNoOverflow($i)->endOfMonth(),
                    $request->user()->id,
                    $list_id
                );
            }
            
            $result["data"][] = [
                "name" => $bar,
                "type" => 'bar',
                "data" => $data,
                "itemStyle" => [
                    "normal" => [
                        "label" => [
                            "show" => true,
                            "textStyle" => [
                                "fontWeight" => 500
                            ]
                        ]
                    ]
                ]
            ];
        }
        
        
        return json_encode($result);
    }
    
    /**
     * 24-hour chart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function quickView(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->uid);
        
        if(!is_object($list)) {
            $list = new \Mailixa\Model\MailList();
            $list->uid = "000";
        }
        
        return view('lists._quick_view', [
            'list' => $list
        ]);
    }
}
