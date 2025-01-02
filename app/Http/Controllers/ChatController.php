<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Mensagem;
use app\Models\Chat;
use app\Models\Modelo;
use App\Services\GPTService;

class ChatController extends Controller
{
    public function startChat(Request $request)
    {
        $token = $request->query('token');
        $modelo = Modelo::where('token', $token)->firstOrFail();

        $chat = Chat::create([
            'user_id' => $modelo->user_id,
            'modelo_id' => $modelo->id,
        ]);

        return response()->json(['chat_id' => $chat->id]);
    }

    public function sendMessage(Request $request)
    {
        $chat = Chat::findOrFail($request->chat_id);
        $message = $request->message;

        // Envia a mensagem para o GPT
        $gptResponse = app(GPTService::class)->enviarMensagem($message);

        // Salva no banco
        Mensagem::create([
            'chat_id' => $chat->id,
            'conteudo' => $message,
            'remetente' => 'cliente',
        ]);

        Mensagem::create([
            'chat_id' => $chat->id,
            'conteudo' => $gptResponse,
            'remetente' => 'gpt',
        ]);

        return response()->json(['response' => $gptResponse]);
    }
}
