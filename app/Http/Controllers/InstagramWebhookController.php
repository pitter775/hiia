<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstagramConta;
use App\Models\EventoInstagram;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

    // Receber Eventos e Responder Automaticamente
    public function receber(Request $request)
    {
        // Armazenar eventos em cache para monitoramento
        $eventos = cache('instagram_event_log', []);
        $eventos[] = $request->all();
        cache(['instagram_event_log' => $eventos], now()->addMinutes(60)); // Salva por 1 hora

        Log::info('Evento Instagram recebido:', $request->all());

        foreach ($request->entry as $entry) {
            foreach ($entry['changes'] as $change) {
                if ($change['field'] == 'comments') {
                    $this->responderComentario($change['value']);
                }
            }
        }

        return response('OK', 200);
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
