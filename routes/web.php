<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\site\SiteController;
use App\Http\Controllers\cliente\ClienteDashboardController;
use App\Http\Controllers\cliente\ReservaClienteController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\ModeloController;
use App\Http\Controllers\admin\UsuarioController;
use App\Http\Controllers\ImagemSalaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CepController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\admin\InstagramContaController;
use App\Http\Controllers\InstagramWebhookController;
use OpenAI\Laravel\Facades\OpenAI;
use App\Http\Controllers\ImageController;


//integracao instagram facebook


Route::get('/webhook/meta', [InstagramWebhookController::class, 'verificar']);
Route::post('/webhook/meta', [InstagramWebhookController::class, 'receber']);



//images
Route::post('/images/upload', [ImageController::class, 'upload'])->name('images.upload');
Route::get('/images/{modelType}/{modelId}', [ImageController::class, 'get'])->name('images.get');


Route::get('/test-openai', function () {
    $response = OpenAI::chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Seja extremamente resumido e direto.'],
            ['role' => 'user', 'content' => 'como crescem os cabelos?'],
        ],
        'temperature' => 0, // Garante respostas mais objetivas
    ]);

    return response()->json($response->choices[0]->message->content);
});



Route::get('/chat', [SiteController::class, 'showChat'])->name('chat');




// Teste funcionando atualmente
// Route::get('/testar-gpt', function () {
//     $gpt = new GPTService();
//     $resposta = $gpt->enviarMensagem('Olá, GPT! Pode me explicar o que é Laravel?');
//     return $resposta;
// });



Route::get('/debug-usuario', function () {
    if (!auth()->check()) {
        return response()->json(['error' => 'Usuário não autenticado.']);
    }

    return response()->json([
        'id' => auth()->id(),
        'email' => auth()->user()->email,
        'tipo_usuario' => auth()->user()->tipo_usuario,
        'cadastro_completo' => auth()->user()->cadastro_completo,
    ]);
});
Route::get('/politica-privacidade', function () {
    return view('site.politica-privacidade1');
})->name('privacidade');

// Rota pública para o site
Route::get('/', [SiteController::class, 'index'])->name('site.index');



// Outras rotas públicas
Route::view('/politica-de-privacidade', 'politica-de-privacidade')->name('politica.privacidade');
Route::view('/termos-de-servico', 'termos-de-servico')->name('termos.servico');

// Google Login e Cadastro Completo
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/completar-cadastro', [UsuarioController::class, 'mostrarFormularioCompletarCadastro'])->name('completar.cadastro.form');
Route::post('/completar-cadastro', [UsuarioController::class, 'completarCadastro'])->name('completar.cadastro');

// Requisição de busca por CEP
Route::get('/api/cep/{cep}', [CepController::class, 'buscarCep']);




// Rotas para clientes (somente após autenticação)
Route::middleware(['auth'])->group(function () {
    Route::get('/cliente', [ReservaClienteController::class, 'minhasReservas'])->name('cliente.index');
    Route::get('/cliente/reservas', [ReservaClienteController::class, 'minhasReservas'])->name('cliente.reservas');
});

// Rotas para administradores
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Rotas para Modelos
    Route::get('/admin/modelos', [ModeloController::class, 'index'])->name('admin.modelos');
    Route::get('/admin/modelos/rascunho', [ModeloController::class, 'getDraft'])->name('modelos.rascunho');
    // Route::post('/admin/modelos/rascunho', [ModeloController::class, 'saveDraft'])->name('modelos.rascunho');
    Route::match(['POST', 'PUT'], '/admin/modelos/rascunho', [ModeloController::class, 'saveDraft'])->name('modelos.rascunho');

    Route::post('/admin/modelos/criar', [ModeloController::class, 'store'])->name('modelos.criar');
    Route::post('/admin/modelos/ativar', [ModeloController::class, 'activate'])->name('modelos.ativar');
    Route::get('/admin/modelos/dominios', [ModeloController::class, 'getAllowedDomains'])->name('modelos.dominios');
    Route::post('/admin/modelos/dominios/adicionar', [ModeloController::class, 'addDomain'])->name('modelos.add_domain');
    Route::post('/admin/modelos/dominios/remover', [ModeloController::class, 'removeDomain'])->name('modelos.remove_domain');
    Route::post('/admin/modelos/upload-imagem', [ModeloController::class, 'uploadImagem'])->name('modelos.uploadImagem');


    Route::get('/admin/instagram', [InstagramContaController::class, 'index'])->name('admin.instagram.index');
    Route::post('/admin/instagram/conectar', [InstagramContaController::class, 'store'])->name('admin.instagram.conectar');



   
    // Rotas para gerenciamento de usuários
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/admin/usuarios/listar', [UsuarioController::class, 'listar'])->name('admin.usuarios.listar');
    Route::post('/admin/usuarios/cadastrar', [UsuarioController::class, 'cadastrar'])->name('admin.usuarios.cadastrar');
    Route::post('/admin/usuarios/atualizar/{id}', [UsuarioController::class, 'atualizar'])->name('admin.usuarios.atualizar');
    Route::delete('/admin/usuarios/deletar/{id}', [UsuarioController::class, 'deletar'])->name('admin.usuarios.deletar');
    Route::get('/admin/usuarios/detalhes/{id}', [UsuarioController::class, 'detalhes'])->name('admin.usuarios.detalhes');
    Route::post('/admin/usuarios/{user}/toggle-status', [UsuarioController::class, 'toggleStatus'])->name('admin.usuarios.toggle-status');

});

// Requisição para autenticação
require __DIR__.'/auth.php';
