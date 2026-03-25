<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Ai\Agents\AriAssistant;

class ChatController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000'
        ]);
        $userMessage = $request->input('prompt');
        try {
            $response = (new AriAssistant)->prompt($userMessage);
            $ariReply = $response->text;
        } catch (\Exception $e) {
            Log::error('Bronca con el agente Ari: ' . $e->getMessage());
            $ariReply = "Ahorita ando teniendo broncas de conexión con el motor. Intenta en un rato.";
        }
        return response()->json(['reply' => $ariReply]);
    }
}