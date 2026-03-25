<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ClientAuthController extends Controller
{
    /**
     * POST /api/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prenom'      => ['required', 'string', 'max:100'],
            'nom'         => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'telephone'   => ['nullable', 'string', 'max:20'],
            'adresse'     => ['nullable', 'string', 'max:255'],
            'ville'       => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'interests'   => ['nullable', 'array'],
            'interests.*' => ['string'],
            'password'    => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'email'     => $validated['email'],
            'password'  => $validated['password'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse'   => $validated['adresse'] ?? null,
            'ville'     => $validated['ville'] ?? null,
            'role'      => 'client',
        ]);

        $client = Client::create([
            'user_id'     => $user->id,
            'prenom'      => $validated['prenom'],
            'nom'         => $validated['nom'],
            'code_postal' => $validated['code_postal'] ?? null,
            'interests'   => $validated['interests'] ?? [],
        ]);

        $token = $user->createToken('client-token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie.',
            'user'    => $this->format($user, $client),
            'token'   => $token,
        ], 201);
    }

    /**
     * POST /api/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', 'client')
                    ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations de connexion sont incorrectes.'],
            ]);
        }

        $remember  = $request->boolean('remember');
        $expiresAt = $remember ? now()->addDays(30) : now()->addHours(24);

        if (! $remember) {
            $user->tokens()->delete();
        }

        $token = $user->createToken('client-token', ['*'], $expiresAt)->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie.',
            'user'    => $this->format($user, $user->client),
            'token'   => $token,
        ]);
    }

    /**
     * GET /api/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json(['user' => $this->format($user, $user->client)]);
    }

    /**
     * PUT /api/auth/profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user   = $request->user();
        $client = $user->client;

        $validated = $request->validate([
            'prenom'      => ['sometimes', 'string', 'max:100'],
            'nom'         => ['sometimes', 'string', 'max:100'],
            'email'       => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telephone'   => ['nullable', 'string', 'max:20'],
            'adresse'     => ['nullable', 'string', 'max:255'],
            'ville'       => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'interests'   => ['nullable', 'array'],
            'interests.*' => ['string'],
        ]);

        $user->update(array_filter([
            'email'     => $validated['email'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'adresse'   => $validated['adresse'] ?? null,
            'ville'     => $validated['ville'] ?? null,
        ], fn ($v) => $v !== null));

        $client->update(array_filter([
            'prenom'      => $validated['prenom'] ?? null,
            'nom'         => $validated['nom'] ?? null,
            'code_postal' => $validated['code_postal'] ?? null,
            'interests'   => $validated['interests'] ?? null,
        ], fn ($v) => $v !== null));

        return response()->json([
            'message' => 'Profil mis à jour.',
            'user'    => $this->format($user->fresh(), $client->fresh()),
        ]);
    }

    /**
     * PUT /api/auth/password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        $user->tokens()->delete();
        $user->update(['password' => $request->password]);
        $token = $user->createToken('client-token')->plainTextToken;

        return response()->json(['message' => 'Mot de passe modifié.', 'token' => $token]);
    }

    /**
     * POST /api/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie.']);
    }

    /**
     * POST /api/auth/logout-all
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnecté de tous les appareils.']);
    }

    // ─────────────────────────────────────────────────────────────
    private function format(User $user, ?Client $client): array
    {
        return [
            'id'        => $user->id,
            'role'      => 'client',
            'email'     => $user->email,
            'telephone' => $user->telephone,
            'adresse'   => $user->adresse,
            'ville'     => $user->ville,
            'prenom'    => $client?->prenom,
            'nom'       => $client?->nom,
            'code_postal' => $client?->code_postal,
            'interests' => $client?->interests ?? [],
        ];
    }
}