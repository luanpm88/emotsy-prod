<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\Plan;

class PlanController extends Controller
{
    /**
     * Select2 plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        echo \Acelle\Model\Plan::select2($request);
    }

    public function publicListPage(Request $request)
    {
        $plans = Plan::getAvailablePlans();
        $style = $request->style ?? 'default';

        return view('plans.publicListPage', [
            'plans' => $plans,
            'style' => $style,
        ]);
    }
}
