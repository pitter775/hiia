<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstagramConta;
use App\Models\EventoInstagram;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

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

    // Monitor de Webhook
    public function monitor()
    {
        return response()->json(cache('instagram_event_log', []));
    }

    public function receber(Request $request)
    {
        Log::info('Webhook Instagram Recebido:', $request->all());
    
        foreach ($request->entry as $entry) {
            foreach ($entry['changes'] as $change) {
                if ($change['field'] == 'comments') {
                    $this->responderComentarioComGPT($change['value']);
                }
            }
        }
    
        return response('OK', 200);
    }
    
    private function responderComentarioComGPT($commentData)
    {
        $commentId = $commentData['id'] ?? null;
        $comentarioTexto = $commentData['message'] ?? '';
    
        if (!$commentId || !$comentarioTexto) {
            Log::error("Dados insuficientes para responder. Dados recebidos:", $commentData);
            return;
        }
    
        try {
            // 1. Gera a resposta com GPT
            $prompt = "Você é um assistente da Hiia Automação IA. Responda o seguinte comentário de forma simpática e profissional:\n\n\"{$comentarioTexto}\"";
    
            $respostaGPT = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Você é um atendente simpático e profissional.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);
    
            $mensagem = $respostaGPT->choices[0]->message->content;
    
            // 2. Responde o comentário no Instagram
            $accessToken = env('META_ACCESS_TOKEN');
            $url = "https://graph.facebook.com/v22.0/{$commentId}/replies";
    
            $response = Http::post($url, [
                'access_token' => $accessToken,
                'message' => $mensagem,
            ]);
    
            Log::info("Comentário {$commentId} respondido com: {$mensagem}");
            Log::info("Resposta API Instagram:", $response->json());
        } catch (\Exception $e) {
            Log::error("Erro ao responder comentário: " . $e->getMessage());
        }
    }

    // Responder Comentário Automaticamente
    private function responderComentario($commentData)
    {
        $commentId = $commentData['id'] ?? null;
        $message = "Olá! Obrigado por interagir conosco. Como posso te ajudar?";

        if (!$commentId) {
            Log::error("Comentário não encontrado no evento.");
            return;
        }

        // Enviar resposta para o Instagram
        $accessToken = env('META_ACCESS_TOKEN');
        $response = Http::post("https://graph.facebook.com/v16.0/{$commentId}/comments", [
            'message' => $message,
            'access_token' => $accessToken,
        ]);

        Log::info("Resposta enviada para o comentário {$commentId}: {$message}");
        Log::info("Resposta API Instagram:", $response->json());
    }
}
