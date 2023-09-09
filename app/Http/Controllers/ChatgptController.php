<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller as BaseController;
use Acelle\Library\Chatgpt;

class ChatgptController extends BaseController
{
    public function settings(Request $request)
    {
        $chatgpt = Chatgpt::initialize($request->user()->customer);

        if ($request->isMethod('post')) {
            // demo mode
            if (config('app.demo')) {
                return response()->view('chatgpt.settings', [
                    'chatgpt' => $chatgpt,
                    'errors' => collect(['demo' => 'This action is not authorized in demo mode']),
                ]);
            }

            // save Chatgpt setting
            $validator = $chatgpt->saveAPISettings($request->user()->customer, $request->all());

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('chatgpt.settings', [
                    'chatgpt' => $chatgpt,
                    'customer' => $request->user()->customer,
                    'errors' => $validator->errors(),
                ], 400);
            }

            return redirect()->action("ChatgptController@settings")
                ->with('alert-success', trans('chatgpt.settings.updated'));
        }

        return view('chatgpt.settings', [
            'chatgpt' => $chatgpt,
            'customer' => $request->user()->customer,
        ]);
    }
}
