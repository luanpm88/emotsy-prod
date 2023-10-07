<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\Campaign;

class CampaignABController extends Controller
{
    public function create(Request $request)
    {
        $campaign = $request->user()->customer->newDefaultCampaign();

        // authorize
        if (\Gate::denies('create', $campaign)) {
            return $this->noMoreItem();
        }

        $campaign->saveFromArray([
            'type' => Campaign::TYPE_AB_TEST,
        ]);

        return redirect()->action('CampaignController@recipients', [
            'uid' => $campaign->uid
        ]);
    }
}