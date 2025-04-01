<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class GPTService
{
    public function enviarMensagem($prompt, $contexto)
    {
        Log::info('enviarMensagem com contexto');
        Log::info('Contexto do modelo:', ['contexto' => $contexto]);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo', // Use o modelo preferido
            'messages' => [
                ['role' => 'system', 'content' => $contexto], // Contexto do modelo
                ['role' => 'user', 'content' => $prompt],    // Mensagem do usuário
            ],
        ]);

        return $response->choices[0]->message->content;
    }
}
