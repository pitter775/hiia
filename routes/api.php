<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\site\SiteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\InstagramWebhookController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Rota de Verificação do Webhook do Instagram/Facebook
Route::get('/webhook/meta', [InstagramWebhookController::class, 'verificar']);
Route::post('/webhook/meta', [InstagramWebhookController::class, 'receber']);

// Rotas do Chat GPT
Route::get('chat-model/{token}', [ChatController::class, 'getChatConfig'])->name('api.chat-model');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('api.chat.send');
Route::get('/chat/start', [ChatController::class, 'startChat'])->name('api.chat.start');

// Rota para Usuário Autenticado (Exemplo)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas de Callback (Google, Instagram, Facebook)
Route::get('/callback/google', [AuthController::class, 'googleCallback'])->name('api.callback.google');
Route::get('/callback/instagram', [AuthController::class, 'instagramCallback'])->name('api.callback.instagram');
Route::get('/callback/facebook', [AuthController::class, 'facebookCallback'])->name('api.callback.facebook');


