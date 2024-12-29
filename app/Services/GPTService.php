<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class GPTService
{
    public function enviarMensagem($prompt)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4', // Use o modelo que preferir (ex.: 'gpt-3.5-turbo')
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um assistente útil.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $response->choices[0]->message->content;
    }
}
