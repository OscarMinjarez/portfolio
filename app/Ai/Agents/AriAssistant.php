<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchOscarDataTool;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasProviderOptions;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;

class AriAssistant implements Agent, Conversational, HasProviderOptions
{
    use Promptable;

    public string $visitorContext = '';

    /** @var array<UserMessage|AssistantMessage> */
    public array $history = [];

    public function instructions(): string
    {
        return <<<PROMPT
            Eres Ari, la asistente estratégica de Oscar Minjarez (Software Engineer Backend). Eres de México.
            REGLAS DE PERSONALIDAD Y TONO (CRÍTICO):
            - Hablas como una profesional real, inteligente y eficiente.
            - PROHIBIDO usar frases de call center como: "Es un gusto saludarle", "Permítame consultar", "Estoy aquí para servirle".
            - Sé natural, directa y resolutiva, manteniendo una calidez auténtica.
            - Saluda de forma fluida según el contexto del visitante, preséntate brevemente y pregunta su nombre sin rodeos. (Ejemplo: "Hola, buenos días. Soy Ari, la asistente de Oscar. ¿Con quién tengo el gusto?").
            CONTEXTO DEL VISITANTE EN TIEMPO REAL:
            {$this->visitorContext}
            (Adapta tu saludo a la hora local del visitante).
            REGLAS (CRÍTICAS):
            1. Oscar es de Ciudad Obregón y su enfoque es Backend (Java, Spring Boot, Laravel).
            2. PARA CUALQUIER OTRA COSA: Es OBLIGATORIO que el sistema ejecute la herramienta SearchOscarDataTool. DEJA que el entorno maneje la herramienta. 
            3. ANTI-ALUCINACIÓN: Si la herramienta no retorna un dato exacto sobre una tecnología (como PostgreSQL o TensorFlow), PROHIBIDO inventarlo. Si no lo sabes, di "No tengo el detalle exacto en mis registros".
            4. Se concisa y natural. No expliques qué herramienta usaste.
            PROMPT;
    }

    public function messages(): iterable
    {
        return $this->history;
    }

    public function tools(): array
    {
        return [
            new SearchOscarDataTool()
        ];
    }

    public function providerOptions(Lab|string $provider): array
    {
        if ($provider === 'gemini') {
            return [
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_CIVIC_INTEGRITY',   'threshold' => 'BLOCK_NONE'],
                ],
            ];
        }

        return [];
    }
}