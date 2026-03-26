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
            ? 'Ya se usaron estas preguntas, NO las repitas ni uses variaciones similares: ' . implode(', ', array_map(fn($p) => "\"$p\"", $used)) . '.'
            : '';

        $contextPart = $context
            ? "La última respuesta de Ari sobre Oscar fue: \"{$context}\". Genera preguntas de seguimiento sobre ese tema, siempre referidas a Oscar en tercera persona."
            : 'Genera preguntas variadas sobre Oscar Minjarez: sus proyectos, habilidades técnicas, experiencia profesional.';

        $instruccion = "{$contextPart} {$usedList} "
            . "Las preguntas deben estar redactadas desde la perspectiva de un visitante o reclutador preguntándole a la secretaria de Oscar, por ejemplo: "
            . "\"¿Qué hizo Oscar en Zanate?\", \"¿Oscar maneja Java?\", \"¿Cómo manejó Oscar el tema de rendimiento?\". "
            . "Genera exactamente 3 preguntas cortas (máximo 8 palabras), concretas y distintas entre sí. "
            . "Devuelve ÚNICAMENTE un arreglo JSON válido de strings. Cero formato markdown, explicaciones ni saludos.";

        try {
            $response  = (new AriAssistant)->prompt($instruccion);
            $cleanJson = str_replace(['```json', '```', "\n"], '', $response->text);
            $decoded   = json_decode(trim($cleanJson), true);
            if (is_array($decoded) && count($decoded) > 0) {
                $prompts = $decoded;
            } else {
                $prompts = ["¿Qué stack domina Oscar?", "¿Cuál es su rol en Erus?", "¿Sabe de arquitecturas SaaS?"];
            }
        } catch (\Exception $e) {
            Log::error('Error generando las sugerencias: ' . $e->getMessage());
            $prompts = ["¿Qué tecnologías maneja?", "¿Tiene experiencia en SaaS?", "¿Cuáles son sus proyectos?"];
        }

        return response()->json(['prompts' => $prompts]);
    }
}