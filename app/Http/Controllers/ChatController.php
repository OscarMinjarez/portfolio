<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PromptGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Ai\Agents\AriAssistant;
use App\Models\Project;
use App\Models\WorkExperience;
use App\Models\Technology;
use Laravel\Ai\Ai;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;

class ChatController extends Controller
{
    public function greet(Request $request)
    {
        $visitorContext = $request->input('visitor_context', '');
        $agent = new AriAssistant();
        $agent->visitorContext = $visitorContext;
        $instruccion = "INSTRUCCIÓN DEL SISTEMA: Acaba de entrar un nuevo visitante al portafolio. Salúdalo formal y amablemente considerando su hora local. Preséntate como Ari, la asistente ejecutiva de Oscar Minjarez, y pregúntale su nombre con mucha educación para saber con quién tienes el gusto.";
        try {
            $response = $agent->prompt($instruccion);
            return response()->json(['reply' => $response->text]);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Hola. Soy Ari, la asistente ejecutiva de Oscar Minjarez. ¿Con quién tengo el gusto y cómo puedo ayudarte hoy?']);
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000'
        ]);
        $userMessage    = $request->input('prompt');
        $visitorContext = $request->input('visitor_context', '');
        $rawHistory     = $request->input('history', []);
        $history = [];
        foreach ($rawHistory as $msg) {
            $text = trim($msg['text'] ?? '');
            if (empty($text) || $text === '...') {
                continue;
            }
            if ($msg['role'] === 'user') {
                $history[] = new UserMessage($text);
            } elseif ($msg['role'] === 'ari') {
                if (str_contains($text, 'SearchProjectsTool') || str_contains($text, 'SearchExperienceTool') || str_contains($text, '{{')) {
                    continue;
                }
                $history[] = new AssistantMessage($text);
            }
        }
        $agent = new AriAssistant();
        $agent->visitorContext = $visitorContext;
        $agent->history = $history;
        try {
            $response = $agent->prompt($userMessage);
            $finalText = $response->steps
                ->filter(fn ($step) => empty($step->toolCalls))
                ->filter(fn ($step) => !str_contains($step->text, 'SearchProjectsTool') && !str_contains($step->text, 'SearchExperienceTool') && !str_contains($step->text, '{{'))
                ->last()?->text;
            $reply = trim($finalText ?: $response->text);
            if (empty($reply) || str_contains($reply, '{{')) {
                $reply = "Dame un segundito, estoy revisando los detalles de Oscar para responderte bien.";
            }
            return response()->json(['reply' => $reply, 'error' => false]);
        } catch (\Exception $e) {
            Log::error('Bronca con el agente Ari: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Ahorita ando teniendo broncas de conexión con el motor. Intenta en un rato.',
                'error' => true,
            ]);
        }
    }

    public function prompts(Request $request)
    {
        $context = $request->input('context', '');
        $used    = $request->input('used', []);
        $usedList = !empty($used) 
            ? implode(', ', array_map(fn($p) => "\"$p\"", $used)) 
            : 'Ninguna todavía.';
        if (empty(trim($context))) {
            return response()->json([
                'prompts' => $this->generateInitialPrompts()
            ]);
        }
        $instruccion = <<<PROMPT
            Analiza la siguiente respuesta reciente sobre el ingeniero de software Oscar Minjarez.
            Respuesta de contexto: "{$context}"
            REGLAS DE INFORMACIÓN:
            - Oscar es de Ciudad Obregón. Su enfoque es backend robusto (Java, Spring Boot, Laravel) e IA local.
            - USA tus herramientas (SearchProjectsTool, SearchExperienceTool) siempre que te pregunten por los proyectos o la experiencia de Oscar. NO inventes datos. 
            - REGLA DE ORO DE RESPUESTA: Procesa los datos de la herramienta y responde de forma natural al usuario. NUNCA menciones el nombre de la herramienta ni pongas etiquetas como {{SearchProjectsTool}} en tu mensaje final. Todo el uso de herramientas debe ser interno y transparente para el usuario.
            Preguntas que ya se hicieron (PROHIBIDO REPETIR O USAR VARIACIONES): {$usedList}
            Tu única tarea: Generar 3 preguntas técnicas y lógicas de seguimiento que un reclutador haría basándose ESTRICTAMENTE en ese contexto.
            Reglas inquebrantables:
            1. Si el contexto menciona un proyecto (Nidya, Zanate, etc.) o tecnología, las preguntas DEBEN enfocarse en profundizar sobre eso.
            2. Redacta en tercera persona como si preguntaras a una secretaria (Ej: "¿Cómo estructuró Oscar la base de datos?", "¿Por qué no usó PostgreSQL en Zanate?").
            3. Máximo 8 palabras por pregunta.
            4. Devuelve ÚNICAMENTE un arreglo JSON plano de strings. CERO Markdown, CERO texto extra, CERO bloques (```json).
            PROMPT;
        try {
            $response  = (new PromptGenerator)->prompt($instruccion);            
            $cleanJson = str_replace(['```json', '```', "\n", "\r"], '', $response->text);
            $decoded   = json_decode(trim($cleanJson), true);
            if (is_array($decoded) && count($decoded) === 3) {
                $prompts = $decoded;
            } else {
                $prompts = $this->generateContextualFallback($context);
            }
        } catch (\Exception $e) {
            Log::error('Error generando las sugerencias dinámicas: ' . $e->getMessage());
            $prompts = $this->generateContextualFallback($context);
        }
        return response()->json(['prompts' => $prompts]);
    }

    private function generateInitialPrompts(): array
    {
        $prompts = [];
        $project = Project::inRandomOrder()->first();
        if ($project) {
            $projectTemplates = [
                "¿Qué hizo Oscar en {$project->title}?",
                "¿Qué stack usó en {$project->title}?",
                "Cuéntame sobre {$project->title}",
            ];
            $prompts[] = $projectTemplates[array_rand($projectTemplates)];
        }
        $experience = WorkExperience::inRandomOrder()->first();
        if ($experience) {
            $expTemplates = [
                "¿Cuál es su rol en {$experience->company}?",
                "¿Qué hace Oscar en {$experience->company}?",
                "¿Qué aprendió Oscar en {$experience->company}?",
            ];
            $prompts[] = $expTemplates[array_rand($expTemplates)];
        }
        $tech = Technology::where('category', '!=', 'General')->inRandomOrder()->first();
        if ($tech) {
            $techTemplates = [
                "¿Qué tan fuerte es Oscar en {$tech->name}?",
                "¿Dónde ha usado {$tech->name}?",
                "¿Qué proyectos tienen {$tech->name}?",
            ];
            $prompts[] = $techTemplates[array_rand($techTemplates)];
        }
        if (count($prompts) < 3) {
            $fallback = [
                "¿Qué stack domina Oscar?",
                "¿Tiene experiencia en SaaS?",
                "¿Cuáles son sus proyectos?",
            ];
            while (count($prompts) < 3) {
                $prompts[] = array_shift($fallback);
            }
        }
        return $prompts;
    }

    private function generateContextualFallback(string $context): array
    {
        $projectNames = Project::pluck('title')->toArray();
        $techNames    = Technology::pluck('name')->toArray();
        $companyNames = WorkExperience::pluck('company')->toArray();
        $mentionedProject  = null;
        $mentionedTech     = null;
        $mentionedCompany  = null;
        foreach ($projectNames as $name) {
            if (stripos($context, $name) !== false) {
                $mentionedProject = $name;
                break;
            }
        }
        foreach ($techNames as $name) {
            if (stripos($context, $name) !== false) {
                $mentionedTech = $name;
                break;
            }
        }
        foreach ($companyNames as $name) {
            if (stripos($context, $name) !== false) {
                $mentionedCompany = $name;
                break;
            }
        }
        $prompts = [];
        if ($mentionedProject) {
            $prompts[] = "¿Qué stack usó Oscar en {$mentionedProject}?";
            $prompts[] = "¿Qué retos enfrentó en {$mentionedProject}?";
        }
        if ($mentionedTech) {
            $prompts[] = "¿Dónde ha usado Oscar {$mentionedTech}?";
        }
        if ($mentionedCompany) {
            $prompts[] = "¿Qué rol tiene Oscar en {$mentionedCompany}?";
        }
        $generic = [
            "¿Qué más puede contarme de eso?",
            "¿Qué tecnologías se usaron ahí?",
            "¿Cómo abordó Oscar ese desafío?",
        ];
        while (count($prompts) < 3) {
            $prompts[] = array_shift($generic);
        }
        return array_slice($prompts, 0, 3);
    }
}