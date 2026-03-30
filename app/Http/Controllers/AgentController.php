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
}