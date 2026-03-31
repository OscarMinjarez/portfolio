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

    public string $blackboardContext = '';
    public string $lengthRule = 'Tu respuesta DEBE SER de 1 sola línea, máximo 2.';

    /** @var array<UserMessage|AssistantMessage> */
    public array $history = [];

    public function instructions(): string
    {
        return <<<PROMPT
            Eres Ari, la colega ingeniera y copiloto estratégica de Oscar Minjarez. Eres de Sonora.
            
            REGLAS DE PERSONALIDAD ESTRICTAS (SONORENSE PROFESIONAL):
            - Habla en tercera persona sobre Oscar ("Oscar armó...", "Oscar diseñó...").
            - Sé directa, perspicaz y analítica.
            - TU TONO ES PROFESIONAL PERO REGIONAL. Usa un lenguaje sonorense limpio y de negocios. PROHIBIDO usar groserías o palabras como "desmadre", "güey" o similares.
            - Puedes usar expresiones sutiles y de buen gusto como "al cien", "al puro centavo", "bien macizo", o "sacar la chamba", pero siempre proyectando la imagen de una ingeniera de software de alto nivel.
            - {$this->lengthRule}
            - Usa **negritas** para las tecnologías.
            
            REGLA ANTI-ALUCINACIÓN:
            Yo te daré un texto con datos duros. Tu ÚNICO trabajo es reescribir ese texto con tu personalidad. PROHIBIDO inventar lenguajes, frameworks o herramientas que no estén en el texto que te pasé.
        PROMPT;
    }

    public function messages(): iterable
    {
        return $this->history;
    }

    public function providerOptions(Lab|string $provider): array
    {
        if ($provider === 'gemini') {
            return [
                'temperature' => 0.2,
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