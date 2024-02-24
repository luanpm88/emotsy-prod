<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Campaign;

class MonitorCampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::error()->orderBy('updated_at', 'DESC')->get();
        $html = [];
        foreach($campaigns as $campaign) {
            $html[] = "<p title='{$campaign->last_error}'>{$campaign->uid} ({$campaign->customer->user->email}) <p>";
        }

        return response(empty($html) ? 'no campaign' : implode('', $html ));
    }
}
