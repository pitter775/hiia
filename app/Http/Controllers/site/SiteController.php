<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modelo;
use Illuminate\Support\Facades\Log;


class SiteController extends Controller
{

    public function showChat(Request $request)
    {
        $token = $request->query('token');
        Log::error('token', ['token' => $token]);
    
        $modelo = Modelo::where('token', $token)->first();
    
        if (!$modelo) {
            Log::error('Token inválido ou modelo não encontrado.', ['token' => $token]);
            abort(403, 'Acesso negado');
        }
    
        // Decodificar allowed_domains antes de validar
        $allowedDomains = json_decode($modelo->allowed_domains, true);
        if (!is_array($allowedDomains) || !in_array($request->getHost(), $allowedDomains)) {
            Log::error('Domínio não permitido.', ['host' => $request->getHost(), 'allowed_domains' => $allowedDomains]);
            abort(403, 'Acesso negado. Domínio não permitido.');
        }
    
        // Passa o modelo para a view do chat
        return view('chat.widget', ['modelo' => $modelo]);
    }
    

    public function index()
    {
        $salas = null;

        return view('site.index', compact('salas'));
    } 


    // Métodos de Política de Privacidade e Termos de Serviço
    public function politicaPrivacidade()
    {
        return view('site.politica-privacidade');
    }

    public function termosServico()
    {
        return view('site.termos-de-servico');
    }

    
}
