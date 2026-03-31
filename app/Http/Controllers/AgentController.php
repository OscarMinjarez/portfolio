<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Technology;
use App\Models\WorkExperience;
use Illuminate\Http\Request;
use App\Ai\Agents\AriAssistant;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function generateInsight(Request $request)
    {
        $focus = $request->input('context.focus', '');
        if (empty($focus)) {
            return response()->json(['insight' => 'Esperando estímulos de la matriz...']);
        }
        $contextoReal = null;
        $project = Project::where('title', 'LIKE', "%{$focus}%")->first();
        if ($project) $contextoReal = $project->ari_context;
        if (!$contextoReal) {
            $job = WorkExperience::where('company', 'LIKE', "%{$focus}%")->first();
            if ($job) $contextoReal = $job->ari_context;
        }
        if (!$contextoReal) {
            $tech = Technology::where('name', 'LIKE', "%{$focus}%")->first();
            if ($tech) $contextoReal = $tech->ari_context;
        }
        if (!$contextoReal) {
            return response()->json(['insight' => "No tengo el reporte técnico de [{$focus}] a la mano, pero pinta bien."]);
        }
        $agent = new AriAssistant();
        try {
            $instruccion = "Reescribe este hecho técnico en 1 línea con tu personalidad y usando negritas para el stack: '{$contextoReal}'";
            $response = $agent->prompt($instruccion);
            $insightText = trim($response->text ?? '');
            $insightText = str_replace(['```json', '```', '```html'], '', $insightText);
            return response()->json(['insight' => trim($insightText)]);
        } catch (\Exception $e) {
            Log::channel('ari')->error('Fallo neuronal en el Blackboard:', ['error' => $e->getMessage()]);
            return response()->json(['insight' => 'Hubo un corto circuito intentando analizar esos datos.'], 500);
        }
    }

    public function generateDetails(Request $request)
    {
        $focus = $request->input('context.focus', '');
        if (empty($focus)) {
            return response()->json(['details' => 'No hay señal clara para analizar...']);
        }
        $contextoReal = null;
        $project = Project::where('title', 'LIKE', "%{$focus}%")->first();
        if ($project) {
            $contextoReal = "[TÍTULO:] {$project->title}\n[RESUMEN:] {$project->summary}\n[CONTENIDO:] {$project->content}\n[README TÉCNICO:]\n" . ($project->readme_content ?? 'No disponible');
        }
        if (!$contextoReal) {
            $job = WorkExperience::where('company', 'LIKE', "%{$focus}%")->first();
            if ($job) {
                $contextoReal = "[EMPRESA:] {$job->company}\n[ROL:] {$job->position}\n[DESCRIPCIÓN:] {$job->description}\n[EXTRA:] {$job->ari_context}";
            }
        }
        if (!$contextoReal) {
            $tech = Technology::where('name', 'LIKE', "%{$focus}%")->first();
            if ($tech) $contextoReal = $tech->ari_context;
        }
        if (!$contextoReal) {
            return response()->json(['details' => "No encuentro los planos arquitectónicos de {$focus}, pero el código seguro compila en mi máquina."]);
        }
        
        $agent = new AriAssistant();
        $agent->lengthRule = 'Tu respuesta DEBE SER elaborada y técnica, estructurada en 2 o 3 párrafos cortos (o viñetas). Usa formato Markdown si es necesario.';
        try {
            $instruccion = "Eres Ari, un ingeniero de software senior revisando este proyecto. Basado en este contexto técnico: '{$contextoReal}', genera una explicación elaborada pero concisa (máximo 3 párrafos o puntos clave) de la arquitectura, decisiones de diseño o el impacto del sistema. Mantén tu tono técnico y directo.";
            $response = $agent->prompt($instruccion);
            $insightText = trim($response->text ?? '');
            $insightText = str_replace(['```json', '```', '```html'], '', $insightText);
            return response()->json(['details' => trim($insightText)]);
        } catch (\Exception $e) {
            Log::channel('ari')->error('Fallo neuronal generando detalles:', ['error' => $e->getMessage()]);
            return response()->json(['details' => 'Un buffer overflow interrumpió la extracción del reporte arquitectónico.'], 500);
        }
    }

    public function generateWelcome(Request $request)
    {
        $agent = new AriAssistant();
        $agent->lengthRule = 'Responder con un saludo súper breve (máximo 1 párrafo o 2 oraciones).';
        try {
            $instruccion = 'Eres Ari, la asistente virtual de Oscar. El usuario acaba de terminar un tutorial interactivo visual. Escribe un mensaje cortito y amigable donde le avises que tú "te quedas aquí guardada en la esquina por si ocupa", y que nomás le dé clic a cualquier proyecto cuando quiera ver los fierros (código). Usa tu tono norteño relajado.';
            $response = $agent->prompt($instruccion);
            $insightText = trim($response->text ?? '');
            $insightText = str_replace(['```json', '```', '```html'], '', $insightText);
            return response()->json(['insight' => trim($insightText)]);
        } catch (\Exception $e) {
            Log::channel('ari')->error('Fallo de saludo:', ['error' => $e->getMessage()]);
            return response()->json(['insight' => '¡Hola! Soy Ari, tu asistente personal hoy. Mi panel de conexión falló un poco, pero explora con confianza y haz clic en los proyectos para ver los detalles.'], 500);
        }
    }
}