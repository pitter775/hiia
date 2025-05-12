<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstagramConta;
use App\Models\EventoInstagram;
use Illuminate\Support\Facades\Log;

class InstagramWebhookController extends Controller
{
    // Validação do webhook (Meta verifica o token)
    public function verificar(Request $request)
    {
        $verify_token = env('META_VERIFY_TOKEN'); // Agora ele pega o token do .env
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
    
    

  // Recebe eventos do Instagram
  public function receber(Request $request)
  {
    Log::info('Evento Instagram recebido:', $request->all());

    foreach ($request->entry as $entry) {
      // IG Business ID
      $igId = $entry['id'] ?? null;

      // Pega a conta vinculada
      $conta = InstagramConta::where('ig_business_id', $igId)->first();

      if ($conta) {
        EventoInstagram::create([
          'instagram_conta_id' => $conta->id,
          'payload' => json_encode($entry),
          'tipo_evento' => $entry['changes'][0]['field'] ?? 'desconhecido',
          'recebido_em' => now(),
        ]);
      }
    }

    return response('OK', 200);
  }
}
