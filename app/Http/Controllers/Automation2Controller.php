<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Acelle\Http\Requests;
use Acelle\Model\Automation2;
use Acelle\Model\MailList;

class Automation2Controller extends Controller
{
    /**
     * Creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = $request->user()->customer;
        
        // init automation
        $automation = new Automation2([
            'name' => trans('messages.automation.untitled')
        ]);
        
        // authorize
        if (\Gate::denies('create', $automation)) {
            return $this->noMoreItem();
        }
        
        // saving
        if($request->isMethod('post')) {
            // fill before save
            $automation->fill($request->all());
            
            // make validator
            $validator = Validator::make($request->all(), $automation->rules());
            
            // redirect if fails
            if ($validator->fails()) {
                return response()->view('automation2.create', [
                    'automation' => $automation,
                    'errors' => $validator->errors(),
                ], 400);
            }
            
            // pass validation and save
            $automation->mail_list_id = MailList::findByUid($request->mail_list_uid)->id;
            $automation->customer_id = $customer->id;
            $automation->save();
            
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.created.redirecting'),
                'url' => action('Automation2Controller@edit', ['uid' => $automation->uid])
            ], 201);
        }
        
        return view('automation2.create', [
            'automation' => $automation,                          
        ]);
    }
    
    /**
     * Creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.edit', [
            'automation' => $automation,                          
        ]);
    }
    
    /**
     * Select trigger type popup.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelectPupop(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        $types = [
            'welcome-new-subscriber',
            'say-happy-birthday',
            'subscriber-added-date',
            'specific-date',
            'say-goodbye-subscriber',
            'api-3-0',
        ];
        
        return view('automation2.triggerSelectPupop', [
            'types' => $types,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select trigger type confirm.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelectConfirm(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.triggerSelectConfirm', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select trigger type.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelect(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Trigger was successfully added.',
            'label' => '[Trigger] ' . trans('messages.automation.trigger.' . $request->type),
        ]);
    }
    
    /**
     * Select action type popup.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionSelectPupop(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        $types = [
            'send-an-email',
            'wait',
            'condition',
        ];
        
        return view('automation2.actionSelectPupop', [
            'types' => $types,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select action type confirm.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionSelectConfirm(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.actionSelectConfirm', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select trigger type.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionSelect(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Action was successfully added.',
            'label' => '[Trigger] ' . trans('messages.automation.action.' . $request->type),
        ]);
    }
    
    /**
     * Edit trigger.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerEdit(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.triggerEdit', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Edit action.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionEdit(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.actionEdit', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
}