<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Sala;
use App\Models\Modelo;

class SiteController extends Controller
{

    public function showChat(Request $request)
    {
        $token = $request->query('token');
        $modelo = Modelo::where('token', $token)->first();

        if (!$modelo || !in_array($request->getHost(), explode(',', $modelo->allowed_domains))) {
            abort(403, 'Acesso negado');
        }

        return view('chat.widget', ['modelo' => $modelo]);
    }

    public function index()
    {
        $salas = null;

        return view('site.index', compact('salas'));
    }
    

    public function detalhes($id)
    {
        // Busca a sala pelo ID, ou retorna 404 se não encontrada
        $sala = null;

        // Renderiza uma view para exibir os detalhes da sala
        return view('site.detalhes', compact('sala'));
    }

    public function revisao(Request $request)
    {
    
        $validated = $request->validate([
            'sala_id' => 'required|exists:salas,id',
            'horarios' => 'required|array|min:1',
            'horarios.*.data_reserva' => 'required|date',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fim' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ]);
    
        try {
            $sala = Sala::with('imagens')->findOrFail($validated['sala_id']);
            $valorTotal = count($validated['horarios']) * $sala->valor;
    
            $imagemPrincipal = $sala->imagens->where('principal', true)->first();
    
            session([
                'reserva' => [
                    'sala_id' => $validated['sala_id'],
                    'sala_nome' => $sala->nome,
                    'imagem_principal' => $imagemPrincipal ? 'storage/' . $imagemPrincipal->path : 'default.jpg',
                    'horarios' => $validated['horarios'],
                    'valor_total' => $valorTotal,
                ],
            ]);
    
            return response()->json(['redirect' => route('reserva.revisao')]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno.', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function exibirRevisao()
    {
        $reserva = session('reserva');
        
        if (!$reserva) {
            return redirect()->route('site.index')->with('error', 'Nenhuma reserva encontrada.');
        }

        $sala = Sala::find($reserva['sala_id']);

        return view('site.revisao', [
            'sala' => $sala,
            'horarios' => $reserva['horarios'],
            'valor_total' => $reserva['valor_total'],
        ]);
    }


    public function confirmar(Request $request)
    {
        $reservaData = session('reserva');
    
        if (!$reservaData) {
            return response()->json(['success' => false, 'message' => 'Reserva inválida.']);
        }
    
        try {
            // Criar a reserva no banco de dados
            foreach ($reservaData['horarios'] as $horario) {
                Reserva::create([
                    'usuario_id' => auth()->id(),
                    'sala_id' => $reservaData['sala_id'],
                    'data_reserva' => $horario['data_reserva'],
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fim' => $horario['hora_fim'],
                ]);
            }
    
            // Limpar a sessão da reserva
            session()->forget('reserva');
    
            return response()->json(['success' => true, 'message' => 'Reserva confirmada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao confirmar a reserva.', 'error' => $e->getMessage()]);
        }
    }
    

    public function salvarReserva(Request $request)
    {
        // Obtém os dados da requisição ou da sessão
        $salaId = $request->input('sala_id', session('reserva.sala_id'));
        $horarios = $request->input('horarios', session('reserva.horarios'));
    
        // Valida se os dados necessários estão presentes
        if (!$salaId || !$horarios) {
            return response()->json([
                'success' => false,
                'message' => 'Dados da reserva estão incompletos ou ausentes.',
            ], 400);
        }
    
        try {
            $sala = Sala::findOrFail($salaId);
            $conflitos = [];
    
            // Verifica conflitos para os horários
            foreach ($horarios as $horario) {
                $conflict = $sala->reservas()
                    ->where('data_reserva', $horario['data_reserva'])
                    ->where(function ($query) use ($horario) {
                        $query->whereBetween('hora_inicio', [$horario['hora_inicio'], $horario['hora_fim']])
                              ->orWhereBetween('hora_fim', [$horario['hora_inicio'], $horario['hora_fim']]);
                    })->exists();
    
                if ($conflict) {
                    $conflitos[] = "{$horario['data_reserva']} - {$horario['hora_inicio']} às {$horario['hora_fim']}";
                }
            }
    
            // Retorna erro se houver conflitos
            if (!empty($conflitos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conflito de horário. A sala já está reservada nos seguintes horários: ' . implode(', ', $conflitos),
                ], 409);
            }
    
            // Cria as reservas
            $reservasCriadas = [];
            foreach ($horarios as $horario) {
                $reserva = $sala->reservas()->create([
                    'data_reserva' => $horario['data_reserva'],
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fim' => $horario['hora_fim'],
                    'usuario_id' => auth()->id() ?? 1, // Usa o usuário logado ou um padrão
                ]);
                $reservasCriadas[] = $reserva;
            }
    
            // Limpa a sessão após criar as reservas
            session()->forget('reserva');
    
            return response()->json([
                'success' => true,
                'reservas' => $reservasCriadas,
                'message' => 'Reserva(s) criada(s) com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar a reserva.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
}
