<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UnifiedAuthController extends Controller
{
    /**
     * POST /api/login
     *
     * Tries in order:
     *   1. User (role = client)
     *   2. User (role = fournisseur)
     *   3. Agent
     *
     * Returns { type, user, token } so the frontend can store the right cookie
     * and redirect to the right page.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // ── 1. Try User (client OR fournisseur) ──────────────────
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            if ($user->role === 'fournisseur') {
                $fournisseur = $user->fournisseur;
                if ($fournisseur?->statut === 'suspendu') {
                    return response()->json(['message' => 'Votre compte a été suspendu.'], 403);
                }
                $user->tokens()->delete();
                $token = $user->createToken('fournisseur-token')->plainTextToken;

                return response()->json([
                    'type'  => 'fournisseur',
                    'token' => $token,
                    'user'  => [
                        'id'             => $user->id,
                        'type'           => 'fournisseur',
                        'email'          => $user->email,
                        'telephone'      => $user->telephone,
                        'adresse'        => $user->adresse,
                        'ville'          => $user->ville,
                        'nom_entreprise' => $fournisseur?->nom_entreprise,
                        'logo'           => $fournisseur?->logo,
                        'statut'         => $fournisseur?->statut,
                        'fournisseur_id' => $fournisseur?->id,
                        'can_manage_agents' => true,
                        'can_edit_profile'  => true,
                    ],
                ]);
            }

            // role = client
            $client = $user->client;
            $user->tokens()->delete();
            $token = $user->createToken('client-token')->plainTextToken;

            return response()->json([
                'type'  => 'client',
                'token' => $token,
                'user'  => [
                    'id'         => $user->id,
                    'role'       => 'client',
                    'email'      => $user->email,
                    'telephone'  => $user->telephone,
                    'adresse'    => $user->adresse,
                    'ville'      => $user->ville,
                    'prenom'     => $client?->prenom,
                    'nom'        => $client?->nom,
                    'code_postal'=> $client?->code_postal,
                    'interests'  => $client?->interests ?? [],
                ],
            ]);
        }

        // ── 2. Try Agent ──────────────────────────────────────────
        $agent = Agent::where('email', $request->email)->first();

        if ($agent && Hash::check($request->password, $agent->password)) {
            if (! $agent->is_active) {
                return response()->json(['message' => 'Votre compte agent est désactivé.'], 403);
            }
            $agent->tokens()->delete();
            $token = $agent->createToken('agent-token')->plainTextToken;

            return response()->json([
                'type'  => 'agent',
                'token' => $token,
                'user'  => [
                    'id'             => $agent->id,
                    'type'           => 'agent',
                    'nom'            => $agent->nom,
                    'prenom'         => $agent->prenom,
                    'email'          => $agent->email,
                    'fournisseur_id' => $agent->fournisseur_id,
                    'nom_entreprise' => $agent->fournisseur?->nom_entreprise,
                    'can_manage_agents' => false,
                    'can_edit_profile'  => false,
                ],
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['Email ou mot de passe incorrect.'],
        ]);
    }
}