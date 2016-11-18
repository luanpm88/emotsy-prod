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
        
        return view('automations.trigger', [
            'automation' => $automation,
        ]);
    }
}
