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
        $mode = $request->query('hub.mode');
        $token = $request->query('hub.verify_token');
        $challenge = $request->query('hub.challenge');
    
        if ($mode === 'subscribe' && $token === 'hiia_token') {
            return response($challenge, 200);
        }
    
        return response('Token inválido', 403);
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
