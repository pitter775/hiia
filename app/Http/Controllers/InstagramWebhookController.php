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

    public function monitor()
    {
        return response()->json(cache('instagram_event_log', []));
    }
    
    // Modificar o método receber para armazenar os eventos recebidos
    public function receber(Request $request)
    {
        // Armazenar os eventos recebidos em cache para monitorar
        $eventos = cache('instagram_event_log', []);
        $eventos[] = $request->all();
        cache(['instagram_event_log' => $eventos], now()->addMinutes(10)); // Salva por 10 minutos
    
        Log::info('Evento Instagram recebido:', $request->all());
    
        foreach ($request->entry as $entry) {
            $igId = $entry['id'] ?? null;
            $conta = InstagramConta::where('ig_business_id', $igId)->first();
            if ($conta) {
                EventoInstagram::create([
                    'instagram_conta_id' => $conta->id,
                    'payload' => json_encode($entry),
                    'tipo_evento' => $entry['changes'][0]['field'] ?? 'desconhecido',
                    'recebido_em' => now(),
                ]);
    
                if ($entry['changes'][0]['field'] == 'comments') {
                    $this->responderComentario($conta, $entry);
                }
            }
        }
    
        return response('OK', 200);
    }

    // Função para responder comentários automaticamente
    // Função para responder comentários automaticamente
    private function responderComentario($conta, $entry)
    {
        $commentId = $entry['changes'][0]['value']['comment_id'] ?? null;
        $message = "Olá! Obrigado por interagir conosco. Como posso te ajudar?";

        if (!$commentId) {
            Log::error("Comentário não encontrado no evento.");
            return;
        }

        // Enviar resposta diretamente para o Instagram usando o Token de Acesso
        $accessToken = env('META_APP_SECRET');
        $response = Http::post("https://graph.facebook.com/v16.0/{$commentId}/replies", [
            'message' => $message,
            'access_token' => $accessToken,
        ]);

        Log::info("Resposta enviada para o comentário {$commentId}: {$message}");
    }

}
