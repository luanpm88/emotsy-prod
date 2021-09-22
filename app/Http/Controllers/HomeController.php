<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\Subscription;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        event(new \Acelle\Events\UserUpdated($request->user()->customer));

        // Last month
        $customer = $request->user()->customer;
        $sendingCreditsUsed = $customer->activeSubscription()->getCreditsUsedDuringPlanCycle('send'); // all time sending credits used
        $sendingCreditsLimit = $customer->activeSubscription()->getCreditsLimit('send');
        $sendingCreditsUsedPercentage = $customer->activeSubscription()->getCreditsUsedPercentageDuringPlanCycle('send');

        return view('dashboard', [
            'sendingCreditsUsed' => $sendingCreditsUsed,
            'sendingCreditsUsedPercentage' => $sendingCreditsUsedPercentage,
            'sendingCreditsLimit' => $sendingCreditsLimit,
        ]);
    }
}
