<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * List all active (non-expired) tokens for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $authUser = $request->user('sanctum') ?? $request->user('agent');

        $currentId = $authUser->currentAccessToken()->id;

        $sessions = $authUser->tokens()
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('last_used_at')
            ->get()
            ->map(fn ($t) => [
                'id'           => $t->id,
                'name'         => $t->name,
                'ip_address'   => $t->ip_address,
                'user_agent'   => $t->user_agent,
                'last_used_at' => $t->last_used_at,
                'expires_at'   => $t->expires_at,
                'created_at'   => $t->created_at,
                'is_current'   => $t->id === $currentId,
            ]);

        return response()->json(['sessions' => $sessions]);
    }

    /**
     * Revoke a specific session by token ID.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $authUser = $request->user('sanctum') ?? $request->user('agent');

        $token = $authUser->tokens()->findOrFail($id);

        // Prevent revoking the current session via this endpoint
        if ($token->id === $authUser->currentAccessToken()->id) {
            return response()->json(['message' => 'Utilisez la déconnexion pour révoquer la session actuelle.'], 422);
        }

        $token->delete();

        return response()->json(['message' => 'Session révoquée.']);
    }

    /**
     * Revoke all sessions except the current one.
     */
    public function destroyOthers(Request $request): JsonResponse
    {
        $authUser  = $request->user('sanctum') ?? $request->user('agent');
        $currentId = $authUser->currentAccessToken()->id;

        $authUser->tokens()->where('id', '!=', $currentId)->delete();

        return response()->json(['message' => 'Toutes les autres sessions ont été révoquées.']);
    }
}
