<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class InstagramController extends Controller
{
    private $token;
    private $instagramId;

    public function __construct()
    {
        $this->token = env('META_ACCESS_TOKEN');
        $this->instagramId = '17841474220225819'; // 🔥 Teu Instagram Business ID
    }

    public function listarMidias()
    {
        $response = Http::get("https://graph.facebook.com/v22.0/{$this->instagramId}/media", [
            'access_token' => $this->token
        ]);

        return $response->json();
    }

    public function listarComentarios($mediaId)
    {
        $response = Http::get("https://graph.facebook.com/v22.0/{$mediaId}/comments", [
            'access_token' => $this->token
        ]);

        return $response->json();
    }

    public function responderUltimoComentario($mediaId)
    {
        $comentarios = Http::get("https://graph.facebook.com/v22.0/{$mediaId}/comments", [
            'access_token' => $this->token
        ])->json();

        if (empty($comentarios['data'])) {
            return response()->json(['message' => 'Nenhum comentário encontrado.']);
        }

        $ultimoComentario = $comentarios['data'][0];
        $comentarioId = $ultimoComentario['id'];

        $mensagem = "Obrigado pelo seu comentário! 😎🚀";

        $resposta = Http::post("https://graph.facebook.com/v22.0/{$comentarioId}/replies", [
            'access_token' => $this->token,
            'message' => $mensagem
        ]);

        return response()->json([
            'comentario_respondido' => $comentarioId,
            'resposta' => $resposta->json()
        ]);
    }

    public function responderComentario(Request $request, $commentId)
    {
        $mensagem = $request->input('mensagem');

        $resposta = Http::post("https://graph.facebook.com/v22.0/{$commentId}/replies", [
            'access_token' => $this->token,
            'message' => $mensagem
        ]);

        return $resposta->json();
    }

    public function responderUltimoComentarioComGPT($mediaId)
    {
        $token = env('META_ACCESS_TOKEN');

        // 1. Buscar comentários
        $comentarios = Http::get("https://graph.facebook.com/v22.0/{$mediaId}/comments", [
            'access_token' => $token
        ])->json();

        if (empty($comentarios['data'])) {
            return response()->json(['message' => 'Nenhum comentário encontrado.']);
        }

        $ultimoComentario = $comentarios['data'][0];
        $comentarioTexto = $ultimoComentario['text'];
        $comentarioId = $ultimoComentario['id'];

        // 2. Envia pro GPT gerar resposta
        $prompt = "Você é um assistente da empresa Hiia Automação IA. Responda de forma educada, simpática e profissional o seguinte comentário no Instagram:\n\n\"{$comentarioTexto}\"";

        $respostaGPT = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo', // ou 'gpt-4' se quiser
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um atendente simpático e profissional.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $mensagemGerada = $respostaGPT->choices[0]->message->content;

        // 3. Envia a resposta no comentário
        $resposta = Http::post("https://graph.facebook.com/v22.0/{$comentarioId}/replies", [
            'access_token' => $token,
            'message' => $mensagemGerada
        ]);

        return response()->json([
            'comentario_respondido' => $comentarioId,
            'comentario_recebido' => $comentarioTexto,
            'mensagem_enviada' => $mensagemGerada,
            'resposta' => $resposta->json()
        ]);
    }
}
