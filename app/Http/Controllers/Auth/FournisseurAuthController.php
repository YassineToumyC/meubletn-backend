<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Fournisseur;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class FournisseurAuthController extends Controller
{
    /**
     * POST /api/fournisseur/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom_entreprise' => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Password::min(8)],
            'telephone'      => ['nullable', 'string', 'max:20'],
            'adresse'        => ['nullable', 'string', 'max:255'],
            'ville'          => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'email'     => $validated['email'],
            'password'  => $validated['password'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse'   => $validated['adresse'] ?? null,
            'ville'     => $validated['ville'] ?? null,
            'role'      => 'fournisseur',
        ]);

        $fournisseur = Fournisseur::create([
            'user_id'        => $user->id,
            'nom_entreprise' => $validated['nom_entreprise'],
        ]);

        $newToken = $user->createToken('fournisseur-token', ['*'], now()->addDays(30));
        $newToken->accessToken->update([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        $token = $newToken->plainTextToken;

        return response()->json([
            'message' => 'Compte fournisseur créé avec succès.',
            'user'    => $this->formatFournisseur($user, $fournisseur),
            'token'   => $token,
        ], 201);
    }

    /**
     * POST /api/fournisseur/login
     * Accepte fournisseur (via users) ou agent (via agents)
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        $remember  = $request->boolean('remember');
        $expiresAt = $remember ? now()->addDays(30) : now()->addHours(24);

        // Try fournisseur (User with role=fournisseur)
        $user = User::where('email', $request->email)
                    ->where('role', 'fournisseur')
                    ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $fournisseur = $user->fournisseur;
            if ($fournisseur?->statut === 'suspendu') {
                return response()->json(['message' => 'Votre compte a été suspendu.'], 403);
            }
            if (! $remember) $user->tokens()->delete();
            $newToken = $user->createToken('fournisseur-token', ['*'], $expiresAt);
            $newToken->accessToken->update([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            $token = $newToken->plainTextToken;
            return response()->json([
                'message' => 'Connexion réussie.',
                'user'    => $this->formatFournisseur($user, $fournisseur),
                'token'   => $token,
            ]);
        }

        // Try agent
        $agent = Agent::where('email', $request->email)->first();
        if ($agent && Hash::check($request->password, $agent->password)) {
            if (! $agent->is_active) {
                return response()->json(['message' => 'Votre compte agent est désactivé.'], 403);
            }
            if (! $remember) $agent->tokens()->delete();
            $newToken = $agent->createToken('agent-token', ['*'], $expiresAt);
            $newToken->accessToken->update([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            $token = $newToken->plainTextToken;
            return response()->json([
                'message' => 'Connexion réussie.',
                'user'    => $this->formatAgent($agent),
                'token'   => $token,
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['Les informations de connexion sont incorrectes.'],
        ]);
    }

    /**
     * GET /api/fournisseur/me
     */
    public function me(Request $request): JsonResponse
    {
        $user  = $request->user('sanctum');
        $agent = $request->user('agent');

        if ($agent) {
            return response()->json(['user' => $this->formatAgent($agent)]);
        }

        return response()->json(['user' => $this->formatFournisseur($user, $user->fournisseur)]);
    }

    /**
     * POST /api/fournisseur/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user('sanctum') ?? $request->user('agent');
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie.']);
    }

    // ─────────────────────────────────────────────────────────────
    private function formatFournisseur(User $user, ?Fournisseur $f): array
    {
        return [
            'id'             => $user->id,
            'type'           => 'fournisseur',
            'email'          => $user->email,
            'telephone'      => $user->telephone,
            'adresse'        => $user->adresse,
            'ville'          => $user->ville,
            'nom_entreprise' => $f?->nom_entreprise,
            'logo'           => $f?->logo,
            'statut'         => $f?->statut,
            'fournisseur_id' => $f?->id,
            'can_manage_agents' => true,
            'can_edit_profile'  => true,
        ];
    }

    private function formatAgent(Agent $a): array
    {
        return [
            'id'             => $a->id,
            'type'           => 'agent',
            'nom'            => $a->nom,
            'prenom'         => $a->prenom,
            'email'          => $a->email,
            'fournisseur_id' => $a->fournisseur_id,
            'nom_entreprise' => $a->fournisseur->nom_entreprise,
            'can_manage_agents' => false,
            'can_edit_profile'  => false,
        ];
    }
}