<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\AutoTrigger as AutoTriggerModel;
use Acelle\Model\Email;
use Exception;

class AutoTrigger extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->id);
        $info = [];
        $info[] = sprintf('This is an auto trigger for automation {{ %s }}', $trigger->automation2->name);
        $info[] = sprintf('Subscriber {{ %s }}', $trigger->subscriber->email);

        $actions = [];
        $trigger->getActions(function ($a) use (&$actions, $trigger) {
            $description = "+ [". (($a->getLastExecuted()) ? "Executed" : "Waiting") . "] " .  $a->getId() . ": " . $a->getTitle();
            if ($a->isCondition()) {
                $description .= " (". $a->getEvaluationResult() .")";
            }

            $actions[] = $description;
        });

        $info[] = implode('<br>', $actions);

        echo implode('<br>', $info);
    }

    public function check(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->id);

        // Execute AutoTrigger#check
        // Notice that calling check() directly against AutoTrigger will not update automation's lastError
        $trigger->check();
        echo "Done";
    }

    public function retry(Request $request)
    {
        // init
        $action = AutoTriggerModel::find($request->id);

        if ($request->isMethod('post')) {
            // do something

            try {
                // $automation->retryAllFailedActions();
    
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.action.retry.success'),
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return view('auto_triggers.retry', [
            'action' => $action,
        ]);
        
    }
}
