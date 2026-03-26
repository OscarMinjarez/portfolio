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
        $keyword = $request->get('keyword');
        $query = WorkExperience::query();
        if ($keyword) {
            $query->where('company', 'LIKE', "%{$keyword}%")
                  ->orWhere('position', 'LIKE', "%{$keyword}%")
                  ->orWhere('ari_context', 'LIKE', "%{$keyword}%");
        }
        $experiences = $query->get(['company', 'position', 'ari_context']);
        if ($experiences->isEmpty()) {
            return "No encontré experiencia laboral relacionada con: {$keyword}.";
        }
        return $experiences->toJson();
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'keyword' => $schema->string()->description('Palabra clave para filtrar la experiencia, ej. "Erus", "Java", "Backend"')->nullable(),
        ];
    }
}
