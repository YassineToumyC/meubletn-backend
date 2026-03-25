<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    private function fournisseurId(Request $request): int
    {
        $agent = $request->user('agent');
        if ($agent instanceof Agent) return $agent->fournisseur_id;

        /** @var User $user */
        $user = $request->user('sanctum');
        abort_if(! $user?->isFournisseur(), 403, 'Accès réservé aux fournisseurs.');
        return $user->fournisseur->id;
    }

    private function agentId(Request $request): ?int
    {
        $agent = $request->user('agent');
        return $agent instanceof Agent ? $agent->id : null;
    }

    public function index(Request $request): JsonResponse
    {
        $fournisseurId = $this->fournisseurId($request);

        $listings = Listing::where('fournisseur_id', $fournisseurId)
            ->with(['category:id,name', 'subcategory:id,name', 'agent:id,nom,prenom'])
            ->latest()
            ->get();

        return response()->json($listings);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titre'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'prix'           => ['nullable', 'numeric', 'min:0'],
            'prix_barre'     => ['nullable', 'numeric', 'min:0'],
            'ville'          => ['nullable', 'string', 'max:100'],
            'category_id'    => ['nullable', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'statut'         => ['nullable', 'in:brouillon,actif,inactif'],
            'images'         => ['nullable', 'array'],
            'images.*'       => ['string'],
        ]);

        $listing = Listing::create([
            ...$validated,
            'fournisseur_id' => $this->fournisseurId($request),
            'agent_id'       => $this->agentId($request),
        ]);

        return response()->json($listing->load(['category:id,name', 'subcategory:id,name']), 201);
    }

    public function show(Request $request, Listing $listing): JsonResponse
    {
        abort_if($listing->fournisseur_id !== $this->fournisseurId($request), 403, 'Accès refusé.');
        return response()->json($listing->load(['category:id,name', 'subcategory:id,name', 'agent:id,nom,prenom']));
    }

    public function update(Request $request, Listing $listing): JsonResponse
    {
        abort_if($listing->fournisseur_id !== $this->fournisseurId($request), 403, 'Accès refusé.');

        $validated = $request->validate([
            'titre'          => ['sometimes', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'prix'           => ['nullable', 'numeric', 'min:0'],
            'prix_barre'     => ['nullable', 'numeric', 'min:0'],
            'ville'          => ['nullable', 'string', 'max:100'],
            'category_id'    => ['nullable', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'statut'         => ['nullable', 'in:brouillon,actif,inactif'],
            'images'         => ['nullable', 'array'],
            'images.*'       => ['string'],
        ]);

        $listing->update($validated);
        return response()->json($listing->fresh()->load(['category:id,name', 'subcategory:id,name']));
    }

    public function destroy(Request $request, Listing $listing): JsonResponse
    {
        abort_if($listing->fournisseur_id !== $this->fournisseurId($request), 403, 'Accès refusé.');
        $listing->delete();
        return response()->json(['message' => 'Annonce supprimée.']);
    }
}