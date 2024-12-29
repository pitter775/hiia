<?php

namespace App\Http\Controllers\admin;;
use App\Models\Sala;  
use App\Models\Endereco;  
use App\Models\Conveniencia;  
use App\Models\ImagemSala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    public function index()
    {
        $enderecos = Endereco::all();
        $salas = Sala::with(['endereco', 'conveniencias'])->get(); // Incluindo conveniências
        $conveniencias = Conveniencia::all(); // Buscando todas as conveniências
    
        return view('admin.salas.index', compact('enderecos', 'salas', 'conveniencias'));
    }
    

    public function create()
    {
        // Buscar todos os endereços
        $enderecos = Endereco::all();

        // Passar os endereços para a view
        return view('admin.salas.create', compact('enderecos'));
    }

    public function getSalaData(Sala $sala)
    {
        $sala->load(['endereco', 'imagens', 'conveniencias']); // Incluindo conveniências no carregamento
    
        return response()->json([
            'sala' => $sala, // Sala com todos os relacionamentos carregados
            'conveniencias' => Conveniencia::all(), // Todas as conveniências disponíveis
            'conveniencias_selecionadas' => $sala->conveniencias->pluck('id') // IDs das conveniências associadas à sala
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'status' => 'required|string',
            'valor' => 'required|numeric',
            'endereco.rua' => 'required|string|max:255',
            'endereco.numero' => 'required|string|max:50',
            'endereco.bairro' => 'required|string|max:255',
            'endereco.cidade' => 'required|string|max:255',
            'endereco.estado' => 'required|string|max:100',
            'endereco.cep' => 'required|string|max:20',
            'endereco.complemento' => 'nullable|string|max:255',
            'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'conveniencias' => 'nullable|array',
            'conveniencias.*' => 'integer|exists:conveniencias,id',
        ]);
    
        $sala = Sala::create($validated);
    
        // Criar o endereço da sala
        $sala->endereco()->create($request->input('endereco'));
    
        // Sincronizar conveniências
        if (!empty($validated['conveniencias'])) {
            $sala->conveniencias()->sync($validated['conveniencias']);
        }
    
        // Lógica de upload de imagens permanece
        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $index => $imagem) {
                $path = $imagem->store('salas', 'public');
                ImagemSala::create([
                    'sala_id' => $sala->id,
                    'path' => $path,
                    'principal' => $index === 0,
                ]);
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Sala criada com sucesso!',
            'data' => $sala->load('endereco', 'imagens', 'conveniencias')
        ]);
    }
       
    
    public function edit(Sala $sala)
    {
        $enderecos = Endereco::all();
        $conveniencias = Conveniencia::all(); // Todas as conveniências disponíveis
        $sala->load('endereco', 'imagens', 'conveniencias'); // Carregar conveniências da sala
    
        return response()->json([
            'sala' => $sala,
            'enderecos' => $enderecos,
            'imagens' => $sala->imagens,
            'conveniencias' => $conveniencias,
            'conveniencias_selecionadas' => $sala->conveniencias->pluck('id') // IDs das conveniências associadas
        ]);
    }
    
    
    public function update(Request $request, Sala $sala)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'status' => 'required|string',
            'valor' => 'required|numeric',
            'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'conveniencias' => 'nullable|array',
            'conveniencias.*' => 'integer|exists:conveniencias,id',
        ]);
    
        // Atualizar os dados da sala
        $sala->update($validated);
    
        // Atualizar conveniências
        $sala->conveniencias()->sync($validated['conveniencias'] ?? []);
    
        // Upload das imagens (caso existam)
        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $index => $imagem) {
                $path = $imagem->store('salas', 'public');
                ImagemSala::create([
                    'sala_id' => $sala->id,
                    'path' => $path,
                    'principal' => $index === 0,
                ]);
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Sala atualizada com sucesso!',
            'data' => $sala->load('endereco', 'imagens', 'conveniencias')
        ]);
    }
    

    public function destroy(Sala $sala)
    {
        $sala->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Sala excluída com sucesso!'
        ]);
    }
    

    public function getSalasData(Request $request)
    {
        $salas = Sala::query();
        
        // Filtro por status
        if ($request->has('status') && !empty($request->status)) {
            $salas->where('status', $request->status);
        }
    
        // Filtro por busca (título ou descrição)
        if ($request->has('busca') && !empty($request->busca)) {
            $busca = $request->busca;
            $salas->where(function ($query) use ($busca) {
                $query->where('nome', 'LIKE', '%' . $busca . '%')
                      ->orWhere('descricao', 'LIKE', '%' . $busca . '%');
            });
        }
    
        // Carregar as salas junto com a imagem principal (relacionamento de imagens)
        $salasList = $salas->with(['imagens' => function ($query) {
            $query->where('principal', true); // Buscar apenas a imagem principal
        }])->get();
    
        $quantidade = $salasList->count(); // Contar o número de salas retornadas
    
        return response()->json([
            'salas' => $salasList, // Retornar as salas filtradas
            'quantidade' => $quantidade // Retornar a quantidade de salas
        ]);
    }
    




    

    
}
