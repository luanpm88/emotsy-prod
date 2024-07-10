<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Library\Tool;
use Acelle\Model\TranslationPhrase;

class TranslationController extends Controller
{
    public function phrases(Request $request)
    {
        $phrases = TranslationPhrase::all();

        // save
        if ($request->isMethod('post')) {


            // success
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.translation.update.success'),
            ]);
        }

        return view('admin.translation.phrases', [
            'phrases' => $phrases,
        ]);
    }
}
