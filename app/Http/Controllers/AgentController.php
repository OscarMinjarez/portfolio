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
        $agent->lengthRule = 'REGLA ESTRICTA DE LONGITUD: Tu respuesta debe ser concisa pero informativa. Extiéndete a 2 o 3 párrafos cortos (entre 80 y 130 palabras en total). No escribas un muro de texto interminable, pero sí da detalles técnicos sustanciosos.';
        try {
            $instruccion = "Eres Ari, una ingeniera de software senior. Contexto técnico puro: '{$contextoReal}'.\nRedacta un resumen bien estructurado sobre la arquitectura y decisiones técnicas de este proyecto. Mantén el tono técnico, formal y norteño.";
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
            $instruccion = 'Eres Ari, la asistente virtual de Oscar. El usuario acaba de terminar un tutorial interactivo. Escribe un mensaje cortito, amigable y muy profesional, donde le avises que te quedas minimizada en la esquina "por si ocupa", y que con gusto le detallarás la arquitectura técnica si da clic a cualquier proyecto. Usa tu tono norteño pero educado y formal, EVITANDO POR COMPLETO palabras ambiguas, doble sentido o coloquialismos vulgares. MUY IMPORTANTE: NO MENCIONES ni listes ninguna tecnología, framework o lenguaje de programación específico en este saludo de bienvenida, mantén tu invitación completamente general a revisar la arquitectura.';
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