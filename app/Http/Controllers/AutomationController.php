<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

use Acelle\Http\Requests;

class AutomationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $automations = $user->automations;

        return view('automations.index', [
            'automations' => $automations,
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $automations = \Acelle\Model\Automation::search($request)->paginate($request->per_page);

        return view('automations._list', [
            'automations' => $automations,
        ]);
    }
    
    /**
     * Creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $automation = new \Acelle\Model\Automation();
        
        // Get old value
        if (null !== $request->old()) {
            $automation->fillAttributes($request->old());
        }

        return view('automations.create', [
            'automation' => $automation,
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
        $current_user = $request->user();
        $automation = new \Acelle\Model\Automation();

        // Create automation
        if ($request->isMethod('post')) {
            // Check validation
            $this->validate($request, $automation->rules()["init"]);
            
            // create automation
            $automation->fillAttributes($request->all());
            $automation->user_id = $current_user->id;
            $automation->save();

            // Redirect to next step
            return redirect()->action('AutomationController@trigger', $automation->uid);
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uid)
    {
        $automation = \Acelle\Model\Automation::findByUid($uid);
        
        // Get old value
        if (null !== $request->old()) {
            $automation->fillAttributes($request->old());
        }

        return view('automations.edit', [
            'automation' => $automation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uid)
    {
        $automation = \Acelle\Model\Automation::findByUid($uid);

        // Update automation
        if ($request->isMethod('patch')) {
            // Check validation
            $this->validate($request, $automation->rules()["init"]);
            
            // create automation
            $automation->fillAttributes($request->all());
            $automation->save();

            // Redirect to next step
            return redirect()->action('AutomationController@trigger', $automation->uid);
        }
    }
    
    /**
     * Trigger for automation.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function trigger(Request $request, $uid)
    {
        $automation = \Acelle\Model\Automation::findByUid($uid);
        
        // Find automation first auto event
        $first_event = $automation->getInitEvent();
        
        // Fill old post data
        if(!empty($request->old())) {
            $first_event->fillAttributes($request->old());
        }
        
        // Save trigger
        if ($request->isMethod('post')) {
            // fill data
            $first_event->fillAttributes($request->all());
            
            // validation
            $this->validate($request, $first_event->rules($request));
            
            // Save to database
            $first_event->save();
        }
        
        return view('automations.trigger', [
            'automation' => $automation,
            'first_event' => $first_event,
            'old' => $request->old(),
        ]);
    }
    
    /**
     * Trigger for automation.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function criteriaForm(Request $request, $uid)
    {
        $automation = \Acelle\Model\Automation::findByUid($uid);
        
        return view('automations._criteria_form', [
            'automation' => $automation,
        ]);
    }
    
    /**
     * Workflows for automation.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function workflow(Request $request, $uid)
    {
        $automation = \Acelle\Model\Automation::findByUid($uid);
        
        // Save workflows
        if ($request->isMethod('post')) {
            
        }
        
        return view('automations.workflow', [
            'automation' => $automation,
            'old' => $request->old(),
        ]);
    }
    
    /**
     * Next auto event form.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function nextEventForm(Request $request, $uid)
    {
        $automation = \Acelle\Model\Automation::findByUid($uid);
        
        return view('automations._next_event_form', [
            'automation' => $automation,
        ]);
    }
}
