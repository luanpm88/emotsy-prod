<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class AudienceController extends Controller
{
    public function overview(Request $request)
    {
        return view('audience.overview');
    }

    public function growthChart(Request $request)
    {
        $result = [
            'columns' => [],
            'total' => [],
            'unsubscribed' => [],
        ];

        $times = [];

        // columns
        for ($i = 15; $i >= 0; --$i) {
            $time = \Acelle\Library\Tool::dateTime(\Carbon\Carbon::now())->subMonths($i);
            $result['columns'][] = $time->format('y M');
            $times[] = $time;
        }

        // data
        foreach ($times as $time) {
            $result['total'][] = \Acelle\Model\Customer::subscribersCountByTime(
                \Carbon\Carbon::now()->subYears(1000),
                $time->endOfDay(),
                $request->user()->customer->id
            );

            $result['unsubscribed'][] = \Acelle\Model\Customer::subscribersCountByTime(
                \Carbon\Carbon::now()->subYears(1000),
                $time->endOfDay(),
                $request->user()->customer->id,
                null,
                \Acelle\Model\Subscriber::STATUS_UNSUBSCRIBED
            );
        }

        return response()->json($result);
    }
}
