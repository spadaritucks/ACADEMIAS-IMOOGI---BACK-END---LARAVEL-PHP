<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function getUserImage($filename)
    {
        // Define o caminho para o arquivo dentro de uploads
        $path = storage_path("app/public/uploads/{$filename}");

        // Verifica se o arquivo existe
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Obtém o conteúdo do arquivo
        $file = file_get_contents($path);

        // Determina o tipo MIME do arquivo
        $mimeType = mime_content_type($path);
        
        // Converte o conteúdo do arquivo para Base64
        $base64 = base64_encode($file);

        // Cria a string Data URL com o tipo MIME correto
        $dataUrl = "data:{$mimeType};base64," . $base64;

        // Define o cabeçalho CORS
        $response = response()->json(['image' => $dataUrl]);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Content-Type', 'application/json');

        return $response;
    }
}