<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstagramConta;
use App\Models\Modelo;

class InstagramContaController extends Controller
{
    public function index()
    {
        $contas = InstagramConta::with('modelo')->get();
        $modelos = Modelo::all();
        $elementActive = 'instagram';

        return view('admin.instagram.index', compact('contas', 'modelos', 'elementActive'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'modelo_id' => 'required|exists:modelos,id',
            'ig_business_id' => 'required|unique:instagram_contas',
            'nome_conta' => 'nullable|string',
            'token' => 'required|string',
        ]);

        InstagramConta::create($request->only(['modelo_id', 'ig_business_id', 'nome_conta', 'token']));

        return redirect()->back()->with('success', 'Conta vinculada com sucesso!');
    }
}

