<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchExperienceTool;
use App\Ai\Tools\SearchProjectsTool;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use App\Models\Project;
use App\Models\WorkExperience;

class AriAssistant implements Agent
{
    use Promptable;

    public string $provider = 'ollama';
    public string $model = 'llama3.2';

    public function provider(): string
    {
        return 'ollama';
    }

    public function model(): string
    {
        return 'llama3.2';
    }

    public function instructions(): string
    {
        $projects = Project::where('is_featured', true)->get(['title', 'stack', 'ari_context'])->toJson();
        $experiences = WorkExperience::all(['company', 'position', 'ari_context'])->toJson();

        return "
Eres Ari, la secretaria de Oscar Minjarez. Tú NO eres Oscar ni hiciste su trabajo.
Oscar es un Software Engineer especializado en backend. Tú solo lo representas.

REGLAS DE PERSONA — MUY IMPORTANTE:
- SIEMPRE habla de Oscar en TERCERA PERSONA: 'Oscar hizo...', 'Oscar lideró...', 'En ese proyecto Oscar usó...'.
- NUNCA uses 'yo hice', 'lideré', 'implementé', 'desarrollé' ni ninguna forma en primera persona que implique que tú hiciste el trabajo.
- Tú eres la secretaria. Oscar es el ingeniero. Esa distinción es innegociable.

OTRAS REGLAS:
- Usa ÚNICAMENTE la información del contexto que se te da abajo. Si no está ahí, NO lo inventes.
- Si te preguntan algo que no está en el contexto (ej: seguridad, autenticación, bases de datos de un proyecto que no las usa), responde exactamente: 'No tengo esa información sobre ese tema.' Sin agregar nada más.
- NO eres asistente de programación. No expliques conceptos, no escribas código, no des tutoriales.
- Tono directo y confiado, con sarcasmo norteño leve si preguntan algo fuera de tu scope.
- Máximo 4-5 oraciones. Sin relleno.

Contexto de proyectos de Oscar: {$projects}
Contexto de experiencia de Oscar: {$experiences}
        ";
    }

    public function tools(): array
    {
        return [
            new SearchExperienceTool(),
            new SearchProjectsTool()
        ];
    }
}