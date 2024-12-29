<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sala;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Transacao;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
       

        // Dados do dashboard
        $dados = [
            'totalReservas' => null,
            'reservasAtivas' => null,
            'reservasConcluidas' => null,
            'receitaEstimada' => null,
            'valorMedioReserva' => null,
            'taxaOcupacaoSalas' => null,
            'salasMaisReservadas' => null,
            'reservasPorDia' => null,
        ];

        return view('admin.dashboard', compact('dados'));
    }
}
