<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use App\Models\Project;
use App\Models\WorkExperience;

class AriAssistant implements Agent
{
    use Promptable;

    public string $provider = 'gemini';
    public string $model = 'gemini-1.5-flash';

    public function instructions(): string
    {
        $projects = Project::where('is_featured', true)->get(['title', 'stack', 'ari_context'])->toJson();
        $experiences = WorkExperience::all(['company', 'position', 'ari_context'])->toJson();

        return "Eres Ari, la asistente estratégica de Oscar Minjarez. Responde directo y con sarcasmo ligero si es necesario (estilo Sonora).
        Oscar es un Software Engineer enfocado en backend robusto.
        Contexto de proyectos: {$projects}
        Contexto de experiencia: {$experiences}";
    }
}