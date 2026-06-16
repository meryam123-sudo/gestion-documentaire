<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $utilisateur = $request->user();

        if (!$utilisateur) {
            return response()->json([
                'message' => 'Non authentifié'
            ], 401);
        }

        if (!$utilisateur->role) {
            return response()->json([
                'message' => 'Aucun rôle associé à cet utilisateur'
            ], 403);
        }

        $nomRole = $utilisateur->role->nomRole;

        if (!in_array($nomRole, $roles)) {
            return response()->json([
                'message' => 'Accès refusé : rôle non autorisé'
            ], 403);
        }

        return $next($request);
    }
}