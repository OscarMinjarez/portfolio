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
        $keyword = $request->get('keyword');
        $query = Project::where('is_featured', true)->query();
        if ($keyword) {
            $query->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('stack', 'LIKE', "%{$keyword}%")
                  ->orWhere('ari_context', 'LIKE', "%{$keyword}%");
        }
        $projects = $query->get(['title', 'stack', 'ari_context']);
        if ($projects->isEmpty()) {
            return "No encontré proyectos relacionados con: {$keyword}.";
        }
        return $projects->toJson();
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'value' => $schema->string()->required(),
        ];
    }
}
