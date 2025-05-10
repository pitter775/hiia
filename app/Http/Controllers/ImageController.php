<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // Método para upload e armazenamento em Base64
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'model_type' => 'required|string',
            'model_id' => 'required|integer'
        ]);

        $file = $request->file('image');
        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $mimeType = $file->getClientMimeType();
        
        // Identificar o modelo associado
        $modelType = 'App\\Models\\' . $request->model_type;
        $model = $modelType::find($request->model_id);

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Modelo não encontrado.'], 404);
        }

        // Verificar se já existe uma imagem associada
        if ($model->image) {
            $model->image->update([
                'base64' => $base64,
                'mime_type' => $mimeType
            ]);
        } else {
            $model->image()->create([
                'base64' => $base64,
                'mime_type' => $mimeType
            ]);
        }

        return response()->json([
            'success' => true,
            'image' => 'data:' . $mimeType . ';base64,' . $base64
        ]);
    }
}


