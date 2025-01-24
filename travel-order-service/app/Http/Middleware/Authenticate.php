<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            // Retorna uma resposta JSON personalizada em caso de falta ou erro no token
            return response()->json([
                'message' => 'Token de autenticação não fornecido ou inválido. Por favor, faça login para acessar esta rota.'
            ], 401); // Retorna código 401 (Unauthorized)
        }
    
        // Para uma API, você não deve retornar uma URL de login. Retorne null.
        return null;
    }
    
}
