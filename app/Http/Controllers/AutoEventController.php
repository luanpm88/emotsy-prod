<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

use Acelle\Http\Requests;

class AutoEventController extends Controller
{
    /**
     * List campaigns of auto event.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function campaigns(Request $request, $uid)
    {
        $auto_event = \Acelle\Model\AutoEvent::findByUid($uid);
        $campaigns = $auto_event->campaigns;
        
        return view('auto_events.campaigns', [
            'auto_event' => $auto_event,
            'campaigns' => $campaigns
        ]);
    }
    
    /**
     * Add campaign to auto event.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function addCampaign(Request $request, $uid)
    {
        $auto_event = \Acelle\Model\AutoEvent::findByUid($uid);
        
        $auto_event->addCampaign();
    }
    
    /**
     * Delete campaign.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCampaign(Request $request, $uid)
    {
        $campaign = \Acelle\Model\Campaign::findByUid($uid);
        
        $campaign->delete();
    }
    
    /**
     * Delete auto event.
     *
     * @param \Illuminate\Http\Request $request
     * @param String                   $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $uid)
    {
        $auto_event = \Acelle\Model\AutoEvent::findByUid($uid);
        
        $auto_event->delete();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uid)
    {
        // Generate info
        $auto_event = \Acelle\Model\AutoEvent::findByUid($uid);

        // validate and save posted data
        if ($request->isMethod('patch')) {
            // fill data
            $auto_event->fillAttributes($request->all());
            $auto_event->save();
            
            echo trans('messages.auto_event.updated');
        }
    }
}
