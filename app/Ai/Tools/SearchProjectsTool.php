<?php

namespace App\Ai\Tools;

use App\Models\Project;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchProjectsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Busca en los proyectos de Oscar Minjarez según la descripción del usuario.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $keyword = $request->get('query');
        $query = Project::query()->where('is_featured', true);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('stack', 'LIKE', "%{$keyword}%")
                  ->orWhere('ari_context', 'LIKE', "%{$keyword}%")
                  ->orWhere('summary', 'LIKE', "%{$keyword}%");
            });
        }

        $projects = $query->take(5)->get(['title', 'summary', 'stack', 'ari_context', 'readme_content']);

        if ($projects->isEmpty()) {
            return "No se encontraron proyectos específicos para: {$keyword}.";
        }

        return $projects->toJson();
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Palabra clave o nombre del proyecto a buscar.')->required(),
        ];
    }
}
