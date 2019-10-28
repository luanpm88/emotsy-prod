<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

use Acelle\Http\Requests;

class Automation2Controller extends Controller
{
    /**
     * Creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('automation2.create');
    }
    
    /**
     * Select trigger type popup.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelectPupop(Request $request)
    {
        sleep(0.5);
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
        ]);
    }
    
    /**
     * Select trigger type confirm.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelectConfirm(Request $request)
    {
        sleep(0.5);
        return view('automation2.triggerSelectConfirm', [
            'type' => $request->type,
        ]);
    }
    
    /**
     * Select trigger type.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelect(Request $request)
    {
        sleep(2);
        
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
    public function actionSelectPupop(Request $request)
    {
        $types = [
            'send-an-email',
            'wait',
            'condition',
        ];
        return view('automation2.actionSelectPupop', [
            'types' => $types,
        ]);
    }
    
    /**
     * Select action type confirm.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionSelectConfirm(Request $request)
    {
        sleep(0.5);
        return view('automation2.actionSelectConfirm', [
            'type' => $request->type,
        ]);
    }
}