<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Ai\Agents\AriAssistant;
use Laravel\Ai\Ai;

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
            return response()->json(['reply' => $response->text, 'error' => false]);
        } catch (\Exception $e) {
            Log::error('Bronca con el agente Ari: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Ahorita ando teniendo broncas de conexión con el motor. Intenta en un rato.',
                'error' => true,
            ]);
        }
    }

    public function prompts(Request $request)
    {
        $context = $request->input('context', '');
        $used    = $request->input('used', []);
        $usedList = !empty($used) 
            ? implode(', ', array_map(fn($p) => "\"$p\"", $used)) 
            : 'Ninguna todavía.';
        if (empty(trim($context))) {
            return response()->json([
                'prompts' => ["¿Qué stack domina Oscar?", "¿Cuál es su rol en Erus?", "¿Sabe de arquitecturas SaaS?"]
            ]);
        }
        $instruccion = <<<PROMPT
            Analiza la siguiente respuesta reciente sobre el ingeniero de software Oscar Minjarez.
            Respuesta de contexto: "{$context}"
            Preguntas que ya se hicieron (PROHIBIDO REPETIR O USAR VARIACIONES): {$usedList}
            Tu única tarea: Generar 3 preguntas técnicas y lógicas de seguimiento que un reclutador haría basándose ESTRICTAMENTE en ese contexto.
            Reglas inquebrantables:
            1. Si el contexto menciona un proyecto (Nidya, Zanate, etc.) o tecnología, las preguntas DEBEN enfocarse en profundizar sobre eso.
            2. Redacta en tercera persona como si preguntaras a una secretaria (Ej: "¿Cómo estructuró Oscar la base de datos?", "¿Por qué no usó PostgreSQL en Zanate?").
            3. Máximo 8 palabras por pregunta.
            4. Devuelve ÚNICAMENTE un arreglo JSON plano de strings. CERO Markdown, CERO texto extra, CERO bloques (```json).
            PROMPT;
        try {
            $response  = (new AriAssistant)->prompt($instruccion);
            $cleanJson = str_replace(['```json', '```', "\n", "\r"], '', $response->text);
            $decoded   = json_decode(trim($cleanJson), true);
            if (is_array($decoded) && count($decoded) === 3) {
                $prompts = $decoded;
            } else {
                $prompts = ["¿Qué base de datos usó ahí?", "¿Cómo resolvió ese problema?", "¿Qué rol tuvo Oscar en esto?"];
            }
        } catch (\Exception $e) {
            Log::error('Error generando las sugerencias dinámicas: ' . $e->getMessage());
            $prompts = ["¿Qué tecnologías maneja?", "¿Tiene experiencia en SaaS?", "¿Cuáles son sus proyectos?"];
        }
        return response()->json(['prompts' => $prompts]);
    }
}