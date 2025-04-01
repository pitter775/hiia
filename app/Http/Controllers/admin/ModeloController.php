<?php

namespace App\Http\Controllers\admin;
use App\Models\Modelo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ModeloController extends Controller
{
    
    // Listar modelos (para o admin ou cliente logado)
    public function index()
    {
        $modelos = Modelo::where('user_id', auth()->id())->get();        
        return view('admin.modelos.index', compact('modelos'));
    }

    public function uploadImagem(Request $request)
    {
        $request->validate([
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $path = $request->file('imagem')->store('public/atendentes');
    
        // Remover 'public/' do caminho para usar com Storage::url()
        $path = str_replace('public/', '', $path);
        $modelo = Modelo::where('user_id', auth()->id())->first();
        // Atualizar o modelo no banco de dados
        // $modelo = Modelo::find($request->id); // Certifique-se de que o ID do modelo é enviado no request
        if ($modelo) {
            $modelo->imagem = $path;
            $modelo->save();
        }
    
        return response()->json([
            'success' => true,
            'caminho' => Storage::url($path) // Retorna URL pública da imagem
        ]);
    }
    
    


    // Salvar Rascunho
    public function saveDraft(Request $request)
    {
        $modelo = Modelo::where('user_id', auth()->id())->latest()->first();
    
        if ($modelo) {
            $modelo->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'dados' => json_encode($request->dados),
            ]);
    
            return response()->json(['nome' => $modelo->nome, 'token' => $modelo->token, 'estado' => 'Rascunho atualizado'], 200);
        }
    
        $modelo = Modelo::create([
            'user_id' => auth()->id(),
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'dados' => json_encode($request->dados),
            'token' => Str::uuid(),
        ]);
    
        return response()->json(['nome' => $modelo->nome, 'token' => $modelo->token, 'estado' => 'Novo rascunho criado'], 200);
    }
    
    
    

    // Verificar se já existe um rascunho
    public function getDraft()
    {
        $modelo = Modelo::where('user_id', auth()->id())
            // ->where('dados->estado', 'Rascunho')
            ->latest()
            ->first();
    
        if ($modelo) {
            $dados = json_decode($modelo->dados, true); // Decodifica o JSON do campo 'dados'
            return response()->json([
                'nome' => $modelo->nome,
                'descricao' => $modelo->descricao,
                'estado' => $dados['estado'] ?? '--', // Busca o estado do JSON
                'updated_at' => $modelo->updated_at,
                'token' => $modelo->token,
                'imagem' => $modelo->imagem,
                'activated_at' => $modelo->activated_at,
            ], 200);
        }
    
        return response()->json([
            'nome' => 'não criado',
            'descricao' => '',
            'estado' => 'não criado',
            'updated_at' => '',
            'activated_at' => '',
        ], 200);
    }

    public function addDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required',
        ]);

        $modelo = Modelo::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$modelo) {
            return response()->json(['error' => 'Modelo não encontrado.'], 404);
        }

        $allowedDomains = json_decode($modelo->allowed_domains, true) ?? [];
        if (!in_array($request->domain, $allowedDomains)) {
            $allowedDomains[] = $request->domain;
            $modelo->update(['allowed_domains' => json_encode($allowedDomains)]);
        }

        return response()->json(['message' => 'Domínio adicionado com sucesso!']);
    }

    public function removeDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required|string',
        ]);
    
        $modelo = Modelo::where('user_id', auth()->id())
            ->latest()
            ->first();
    
        if (!$modelo) {
            return response()->json(['error' => 'Modelo não encontrado.'], 404);
        }
    
        $allowedDomains = json_decode($modelo->allowed_domains, true) ?? [];
    
        // Normaliza o domínio do request para remoção
        $normalizedDomain = rtrim(preg_replace('/^(https?:\/\/)/', '', $request->domain), '/');
    
        // Remove o domínio normalizado da lista
        $allowedDomains = array_filter($allowedDomains, function ($domain) use ($normalizedDomain) {
            return rtrim(preg_replace('/^(https?:\/\/)/', '', $domain), '/') !== $normalizedDomain;
        });
    
        $modelo->update(['allowed_domains' => json_encode(array_values($allowedDomains))]);
    
        return response()->json(['message' => 'Domínio removido com sucesso!']);
    }
    




    public function getAllowedDomains()
    {
        $modelo = Modelo::where('user_id', auth()->id())
            ->latest()
            ->first();
    
        if (!$modelo) {
            return response()->json(['allowed_domains' => []], 200); // Retorna array vazio
        }
    
        $allowedDomains = json_decode($modelo->allowed_domains, true) ?? [];
    
        return response()->json(['allowed_domains' => $allowedDomains], 200);
    }
    

    

    // Ativar Atendente
    public function activate()
    {
        $modelo = Modelo::where('user_id', auth()->id())
            ->latest()
            ->first();
    
        if (!$modelo) {
            return response()->json(['error' => 'Nenhum modelo encontrado.'], 404);
        }
    
        // Verificar se existem domínios permitidos configurados
        $allowedDomains = json_decode($modelo->allowed_domains, true);
        if (empty($allowedDomains)) {
            return response()->json(['error' => 'Nenhum domínio permitido configurado.'], 400);
        }
    
        // Gerar identificador único baseado no nome
        $modelIdentifier = Str::slug($modelo->nome, '_') . '_' . Str::random(6);

    
        // Criar conteúdo no GPT
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Crie um modelo de atendimento baseado nos seguintes dados:'],
                ['role' => 'user', 'content' => 'Nome do modelo: ' . $modelo->nome],
                ['role' => 'user', 'content' => 'Descrição: ' . $modelo->descricao],
                ['role' => 'user', 'content' => 'Domínios permitidos: ' . implode(', ', $allowedDomains)],
            ],
            'temperature' => 0.7,
        ]);
    
        $conteudoGerado = $response->choices[0]->message->content ?? 'Erro ao gerar conteúdo no GPT.';
    
        // Atualizar o modelo no banco de dados
        $dados = json_decode($modelo->dados, true);
        $dados['estado'] = 'Ativo';
        $dados['conteudo_gerado'] = $conteudoGerado;
    
        $modelo->update([
            'dados' => json_encode($dados),
            'activated_at' => now(),
            'token' => $modelo->token ?? Str::uuid(), // Garante que o token seja gerado se ainda não existir
            'model_identifier' => $modelIdentifier, // Salva o identificador único
        ]);
    
        return response()->json([
            'nome' => $modelo->nome,
            'estado' => 'Ativo',
            'activated_at' => $modelo->activated_at,
            'conteudo_gerado' => $conteudoGerado,
            'token' => $modelo->token,
            'model_identifier' => $modelIdentifier,
        ], 200);
    }
    
    




    // Editar modelo existente
    public function edit($id)
    {
        $modelo = Modelo::where('user_id', auth()->id())->findOrFail($id);
        return view('modelos.edit', compact('modelo'));
    }

    // Atualizar modelo
    public function update(Request $request, $id)
    {
        $modelo = Modelo::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $modelo->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'dados' => $request->dados,
        ]);

        return redirect()->route('modelos.index')->with('success', 'Modelo atualizado com sucesso!');
    }

    // Deletar modelo
    public function destroy($id)
    {
        $modelo = Modelo::where('user_id', auth()->id())->findOrFail($id);
        $modelo->delete();

        return redirect()->route('modelos.index')->with('success', 'Modelo excluído com sucesso!');
    }
}
