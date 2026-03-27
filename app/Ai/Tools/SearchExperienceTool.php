<?php

namespace App\Ai\Tools;

use App\Models\WorkExperience;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchExperienceTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Busca en la experiencia laboral de Oscar Minjarez según la descripción del usuario.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $keyword = $request->get('query');
        $query = WorkExperience::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('company', 'LIKE', "%{$keyword}%")
                  ->orWhere('position', 'LIKE', "%{$keyword}%")
                  ->orWhere('ari_context', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }

        $experiences = $query->take(5)->get(['company', 'position', 'ari_context', 'description']);

        if ($experiences->isEmpty()) {
            return "No se encontró experiencia laboral relacionada con: {$keyword}.";
        }

        return $experiences->toJson();
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Termino de búsqueda para la experiencia laboral (empresa, rol o tecnología).')->required(),
        ];
    }
}
