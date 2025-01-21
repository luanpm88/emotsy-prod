<?php

namespace Acelle\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use ValidatesRequests;

    /**
     * Check if the user is not authorized.
     *
     * @return \Illuminate\Http\Response
     */
    public function notAuthorized()
    {
        if (request()->ajax()) {
            return response()->json(['message' => trans('messages.not_authorized_message')], 403);
        }

        return response()->view('notAuthorized')->setStatusCode(403);
    }

    /**
     * Check if the user cannot create more item.
     *
     * @return \Illuminate\Http\Response
     */
    public function noMoreItem()
    {
        return view('noMoreItem');
    }
}
