<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Chatgpt\Chatgpt;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $chatgpt = Chatgpt::initialize();
        $messages = $request->messages;

        try {
            ss;
            // try to get response message
            $result = $chatgpt->chat($messages);

            if(count($result['choices'])) {
                $messages[] = $result['choices'][0]['message'];
            }
        } catch (\Throwable $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ], 500);
        }
        

        return response()->json($messages);
    }
}