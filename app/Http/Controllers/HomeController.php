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
        $currentTimezone = $request->user()->customer->getTimezone();

        // Last month
        $customer = $request->user()->customer;

        if (config('app.saas')) {
            $sendingCreditsUsed = $customer->getCurrentActiveGeneralSubscription()->getSendEmailRateTracker()->getCreditsUsed(now()->subtract('30 days'));
            $sendingCreditsLimit = $customer->getCurrentActiveGeneralSubscription()->getCreditsLimit('send');
            $sendingCreditsUsedPercentage = $customer->getCurrentActiveGeneralSubscription()->getRemainingEmailCreditsPercentage();
        } else {
            $sendingCreditsUsed = 0; // all time sending credits used
            $sendingCreditsLimit = -1;
            $sendingCreditsUsedPercentage = 0;
        }

        $maxLists = get_tmp_quota($customer, 'list_max');
        $maxCampaigns = get_tmp_quota($customer, 'campaign_max');
        $maxSubscribers = get_tmp_quota($customer, 'subscriber_max');

        $listsCount = $customer->listsCount();
        $listsUsed = ($maxLists == -1) ? 0 : $listsCount / $maxLists;

        $campaignsCount = $customer->campaignsCount();
        $campaignsUsed = ($maxCampaigns == -1) ? 0 : $campaignsCount / $maxCampaigns;

        $subscribersCount = $customer->readCache('SubscriberCount', 0);
        $subscribersUsed = ($maxSubscribers == -1) ? 0 : $subscribersCount / $maxSubscribers;

        if (config('app.cartpaye')) {
            return view('dashboard.cartpaye');

        } elseif (config('app.emotsy')) {
            $list = new \Acelle\Model\MailList();
            $list->customer_id = $request->user()->customer->id;
            $list->uid = 'all';
            $campaign = $request->campaign_uid ? \Acelle\Model\Campaign::findByUid($request->campaign_uid) : $customer->campaigns()->done()->first();

            return view('emotsy.dashboard', [
                'sendingCreditsUsed' => $sendingCreditsUsed,
                'sendingCreditsUsedPercentage' => $sendingCreditsUsedPercentage,
                'sendingCreditsLimit' => $sendingCreditsLimit,
                'currentTimezone' => $currentTimezone,
                'maxLists' => $maxLists,
                'listsCount' => $listsCount,
                'listsUsed' => $listsUsed,
                'maxCampaigns' => $maxCampaigns,
                'campaignsCount' => $campaignsCount,
                'campaignsUsed' => $campaignsUsed,
                'maxSubscribers' => $maxSubscribers,
                'subscribersCount' => $subscribersCount,
                'subscribersUsed' => $subscribersUsed,
                'campaign' => $campaign,
                'list' => $list,
            ]);
        } else {
            return view('dashboard', [
                'sendingCreditsUsed' => $sendingCreditsUsed,
                'sendingCreditsUsedPercentage' => $sendingCreditsUsedPercentage,
                'sendingCreditsLimit' => $sendingCreditsLimit,
                'currentTimezone' => $currentTimezone,
                'maxLists' => $maxLists,
                'listsCount' => $listsCount,
                'listsUsed' => $listsUsed,
                'maxCampaigns' => $maxCampaigns,
                'campaignsCount' => $campaignsCount,
                'campaignsUsed' => $campaignsUsed,
                'maxSubscribers' => $maxSubscribers,
                'subscribersCount' => $subscribersCount,
                'subscribersUsed' => $subscribersUsed,
            ]);
        }
    }
}
