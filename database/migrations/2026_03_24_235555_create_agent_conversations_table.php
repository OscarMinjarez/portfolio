<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Facades\AI;

class ChatController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000'
        ]);
        $userMessage = $request->input('prompt');
        $projects = Project::where('is_featured', true)->get(['title', 'stack', 'ari_context']);
        $experiences = WorkExperience::all(['company', 'position', 'ari_context']);
        $systemInstruction = "Eres Ari, la asistente estratégica de Oscar Minjarez. Responde directo y con sarcasmo ligero si es necesario (estilo Sonora). 
        Oscar es un Software Engineer enfocado en backend robusto. 
        Contexto de proyectos: {$projects->toJson()}
        Contexto de experiencia: {$experiences->toJson()}";
        try {
            $response = AI::agent('ari')
                ->system($systemInstruction)
                ->conversation(session()->getId())
                ->chat($userMessage);

            $ariReply = $response->text();
            
        } catch (\Exception $e) {
            Log::error('Bronca con Laravel AI: ' . $e->getMessage());
            $ariReply = "Ahorita ando teniendo broncas de conexión con el motor. Intenta en un rato.";
        }
        return response()->json(['reply' => $ariReply]);
    }
}