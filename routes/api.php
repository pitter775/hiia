<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\site\SiteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\InstagramWebhookController;

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
Route::get('chat-model/{token}', [ChatController::class, 'getChatConfig'])->name('api.chat-model');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('api.chat.send');
Route::get('/chat/start', [ChatController::class, 'startChat'])->name('api.chat.start');

// Route::get('chat-model/{token}', function ($token) {
//     return response()->json([
//         'nome' => 'Teste Chat',
//         'logo' => 'imagens/teste-logo.png',
//     ]);
// });



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware([])->group(function () {
//     Route::get('/webhook/meta', [InstagramWebhookController::class, 'verificar']);
//     Route::post('/webhook/meta', [InstagramWebhookController::class, 'receber']);
//   });