<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensagem;
use App\Models\Chat;
use App\Models\Modelo;
use App\Services\GPTService;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function startChat(Request $request)
    {
        Log::info('startChat');
        $token = $request->query('token');
        Log::info('Token recebido no startChat:', ['token' => $token]);
        $modelo = Modelo::where('token', $token)->firstOrFail();

        if (!$modelo) {
            Log::error('Modelo não encontrado para o token:', ['token' => $token]);
            abort(403, 'Token inválido.');
        }

        Log::info('Modelo encontrado:', $modelo->toArray());
    
        $chat = Chat::create([
            'modelo_id' => $modelo->id,
        ]);
    
        return response()->json(['chat_id' => $chat->id]);
    }


    public function getChatConfig($token)
    {
        Log::info('getChatConfig');

        // Busca o modelo pelo token
        $modelo = Modelo::where('token', $token)->first();
    
        if (!$modelo) {
            return response()->json(['error' => 'Token inválido.'], 403);
        }
    
        // Verifica se já existe um chat para esse modelo
        $chat = Chat::where('modelo_id', $modelo->id)->first();
    
        if (!$chat) {
            Log::info('Nenhum chat encontrado para o modelo, criando um novo.');
            $chat = Chat::create(['modelo_id' => $modelo->id]);
        }
    
        return response()->json([
            'chat_id' => $chat->id, // Agora já retorna o ID do chat
            'nome' => $modelo->nome,
            'logo' => $modelo->imagem ? 'storage/' . $modelo->imagem : 'assets/img/semfoto.jpg',
        ]);
    }
    


    public function sendMessage(Request $request)
    {
        Log::info('sendMessage');
        Log::info('Dados recebidos:', ['request' => $request->all()]);
        $chat = Chat::findOrFail($request->chat_id);
        Log::info('Chat encontrado no sendMessage:', ['chat_id' => $chat->id]);
    
        $message = $request->message;
        Log::info('Mensagem enviada pelo cliente:', ['message' => $message]);
    
        // Buscar o modelo associado ao chat
        $modelo = $chat->modelo;
        Log::info('Modelo associado ao chat:', ['modelo_id' => $modelo->id, 'descricao' => $modelo->descricao]);
    
        // Envia a mensagem para o GPT com o contexto do modelo
        $gptResponse = app(GPTService::class)->enviarMensagem($message, $modelo->descricao);
        Log::info('Resposta do GPT:', ['response' => $gptResponse]);
    
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
