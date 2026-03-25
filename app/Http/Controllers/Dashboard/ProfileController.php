<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    private function fournisseur(Request $request)
    {
        /** @var User $user */
        $user = $request->user('sanctum');
        abort_if(! $user?->isFournisseur(), 403, 'Accès réservé aux fournisseurs.');
        return [$user, $user->fournisseur];
    }

    public function show(Request $request): JsonResponse
    {
        [$user, $f] = $this->fournisseur($request);

        return response()->json([
            'id'             => $user->id,
            'nom_entreprise' => $f->nom_entreprise,
            'email'          => $user->email,
            'telephone'      => $user->telephone,
            'adresse'        => $user->adresse,
            'ville'          => $user->ville,
            'logo'           => $f->logo,
            'statut'         => $f->statut,
            'agents_count'   => $f->agents()->count(),
            'listings_count' => $f->listings()->count(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        [$user, $f] = $this->fournisseur($request);

        $validated = $request->validate([
            'nom_entreprise' => ['sometimes', 'string', 'max:255'],
            'email'          => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telephone'      => ['nullable', 'string', 'max:20'],
            'adresse'        => ['nullable', 'string', 'max:255'],
            'ville'          => ['nullable', 'string', 'max:100'],
        ]);

        $user->update(array_intersect_key($validated, array_flip(['email', 'telephone', 'adresse', 'ville'])));

        if (isset($validated['nom_entreprise'])) {
            $f->update(['nom_entreprise' => $validated['nom_entreprise']]);
        }

        return response()->json(['message' => 'Profil mis à jour.', 'nom_entreprise' => $f->fresh()->nom_entreprise]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        [$user] = $this->fournisseur($request);

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
        $token = $user->createToken('fournisseur-token')->plainTextToken;

        return response()->json(['message' => 'Mot de passe modifié.', 'token' => $token]);
    }

    public function stats(Request $request): JsonResponse
    {
        [$user, $f] = $this->fournisseur($request);

        return response()->json([
            'total_listings'     => $f->listings()->count(),
            'actif_listings'     => $f->listings()->where('statut', 'actif')->count(),
            'brouillon_listings' => $f->listings()->where('statut', 'brouillon')->count(),
            'inactif_listings'   => $f->listings()->where('statut', 'inactif')->count(),
            'total_agents'       => $f->agents()->count(),
            'actif_agents'       => $f->agents()->where('is_active', true)->count(),
            'total_vues'         => $f->listings()->sum('vues'),
        ]);
    }
}