<?php

namespace App\Ai\Tools;

use App\Models\Project;
use App\Models\WorkExperience;
use App\Models\Technology;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchOscarDataTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Busca información técnica, laboral y de proyectos de Oscar Minjarez (Java, Laravel, Spring Boot, etc.).';
    }

    public function handle(Request $request): Stringable|string
    {
        $queryText = $request->get('query');
        \Illuminate\Support\Facades\Log::channel('ari')->info("🛠️ Ari usó la herramienta buscando: [{$queryText}]");
        if (empty($queryText)) {
            return "Indica un término de búsqueda para consultar la base de datos de Oscar.";
        }
        $projects = Project::where('title', 'LIKE', "%{$queryText}%")
            ->orWhere('stack', 'LIKE', "%{$queryText}%")
            ->orWhere('summary', 'LIKE', "%{$queryText}%")
            ->orWhere('ari_context', 'LIKE', "%{$queryText}%")
            ->take(3)
            ->get(['title', 'stack', 'ari_context', 'summary']);
        $experience = WorkExperience::where('company', 'LIKE', "%{$queryText}%")
            ->orWhere('position', 'LIKE', "%{$queryText}%")
            ->orWhere('description', 'LIKE', "%{$queryText}%")
            ->orWhere('ari_context', 'LIKE', "%{$queryText}%")
            ->take(3)
            ->get(['company', 'position', 'description', 'ari_context']);
        $techs = Technology::where('name', 'LIKE', "%{$queryText}%")
            ->orWhere('category', 'LIKE', "%{$queryText}%")
            ->orWhere('ari_context', 'LIKE', "%{$queryText}%")
            ->get(['name', 'category', 'ari_context']);
        $result = [
            'proyectos_relevantes' => $projects,
            'experiencia_laboral' => $experience,
            'tecnologias_y_skills' => $techs,
        ];
        if ($projects->isEmpty() && $experience->isEmpty() && $techs->isEmpty()) {
            return "No encontré datos específicos sobre '{$queryText}' en los registros de Oscar.";
        }
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('El término de búsqueda (ej: "Java", "Nidya", "BeAnalítica", "Backend").')->required(),
        ];
    }
}
