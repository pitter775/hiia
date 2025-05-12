<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstagramConta;
use App\Models\EventoInstagram;
use Illuminate\Support\Facades\Log;

class InstagramWebhookController extends Controller
{
    // Verificar Webhook
    public function verificar(Request $request)
    {
        $verify_token = env('META_VERIFY_TOKEN');
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verify_token) {
                return response($challenge, 200);
            } else {
                return response('Verificação falhou', 403);
            }
        }
        return response('Requisição inválida', 400);
    }

    // Receber Eventos e Responder
    public function receber(Request $request)
    {
        Log::info('Evento Instagram recebido:', $request->all());

        foreach ($request->entry as $entry) {
            $igId = $entry['id'] ?? null;

            // Verifica a conta vinculada
            $conta = InstagramConta::where('ig_business_id', $igId)->first();
            if ($conta) {
                // Armazena o evento recebido
                EventoInstagram::create([
                    'instagram_conta_id' => $conta->id,
                    'payload' => json_encode($entry),
                    'tipo_evento' => $entry['changes'][0]['field'] ?? 'desconhecido',
                    'recebido_em' => now(),
                ]);

                // Verifica se é uma mensagem e responde
                if ($entry['changes'][0]['field'] == 'comments') {
                    $this->responderComentario($conta, $entry);
                }
            }
        }

        return response('OK', 200);
    }

    // Função para responder comentários automaticamente
    private function responderComentario($conta, $entry)
    {
        $commentId = $entry['changes'][0]['value']['comment_id'] ?? null;
        $message = "Olá! Obrigado por interagir conosco. Como posso te ajudar?";

        // Simulação da resposta com GPT (pode ser customizado)
        $gptResponse = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um assistente da empresa.'],
                ['role' => 'user', 'content' => "Pergunta do usuário: " . $message],
            ],
            'temperature' => 0.7,
        ]);

        $resposta = $gptResponse->choices[0]->message->content;

        // Aqui seria o código para enviar a resposta para o Instagram (API Meta)
        Log::info("Resposta para o comentário {$commentId}: {$resposta}");
    }
}
