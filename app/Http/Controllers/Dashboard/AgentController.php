<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AgentController extends Controller
{
    private function fournisseur(Request $request)
    {
        /** @var User $user */
        $user = $request->user('sanctum');
        abort_if(! $user?->isFournisseur(), 403, 'Accès réservé aux fournisseurs.');
        return $user->fournisseur;
    }

    public function index(Request $request): JsonResponse
    {
        $fournisseur = $this->fournisseur($request);

        $agents = Agent::where('fournisseur_id', $fournisseur->id)
            ->latest()
            ->get()
            ->map(fn ($a) => [
                'id'         => $a->id,
                'nom'        => $a->nom,
                'prenom'     => $a->prenom,
                'email'      => $a->email,
                'is_active'  => $a->is_active,
                'created_at' => $a->created_at,
            ]);

        return response()->json($agents);
    }

    public function store(Request $request): JsonResponse
    {
        $fournisseur = $this->fournisseur($request);

        $validated = $request->validate([
            'nom'      => ['required', 'string', 'max:100'],
            'prenom'   => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:agents,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $agent = Agent::create([...$validated, 'fournisseur_id' => $fournisseur->id]);

        return response()->json(['message' => 'Agent créé.', 'agent' => [
            'id' => $agent->id, 'nom' => $agent->nom, 'prenom' => $agent->prenom,
            'email' => $agent->email, 'is_active' => $agent->is_active,
        ]], 201);
    }

    public function update(Request $request, Agent $agent): JsonResponse
    {
        $fournisseur = $this->fournisseur($request);
        abort_if($agent->fournisseur_id !== $fournisseur->id, 403, 'Accès refusé.');

        $validated = $request->validate([
            'nom'       => ['sometimes', 'string', 'max:100'],
            'prenom'    => ['sometimes', 'string', 'max:100'],
            'email'     => ['sometimes', 'email', 'max:255', 'unique:agents,email,' . $agent->id],
            'is_active' => ['sometimes', 'boolean'],
            'password'  => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if (empty($validated['password'])) unset($validated['password']);

        $agent->update($validated);

        return response()->json(['message' => 'Agent mis à jour.', 'agent' => [
            'id' => $agent->id, 'nom' => $agent->nom, 'prenom' => $agent->prenom,
            'email' => $agent->email, 'is_active' => $agent->is_active,
        ]]);
    }

    public function destroy(Request $request, Agent $agent): JsonResponse
    {
        $fournisseur = $this->fournisseur($request);
        abort_if($agent->fournisseur_id !== $fournisseur->id, 403, 'Accès refusé.');

        $agent->tokens()->delete();
        $agent->delete();

        return response()->json(['message' => 'Agent supprimé.']);
    }
}