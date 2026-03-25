<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * GET /api/home
     * Returns all homepage sections in a single request.
     */
    public function index(): JsonResponse
    {
        // Nouveautés — 8 latest
        $nouveautes = Announcement::with(['category', 'fournisseur'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($a) => $this->format($a));

        // Sélectionné pour vous — 8 most viewed
        $populaires = Announcement::with(['category', 'fournisseur'])
            ->where('is_active', true)
            ->orderByDesc('views')
            ->limit(8)
            ->get()
            ->map(fn ($a) => $this->format($a));

        // Par catégorie — top 5 categories with 6 products each
        $categories = Category::where('is_active', true)
            ->orderBy('position')
            ->limit(5)
            ->get();

        $parCategorie = $categories->map(function (Category $cat) {
            $products = Announcement::with(['fournisseur'])
                ->where('is_active', true)
                ->where('category_id', $cat->id)
                ->latest()
                ->limit(6)
                ->get()
                ->map(fn ($a) => $this->format($a));

            if ($products->isEmpty()) return null;

            return [
                'category' => [
                    'id'   => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                ],
                'products' => $products,
            ];
        })->filter()->values();

        return response()->json([
            'nouveautes'   => $nouveautes,
            'populaires'   => $populaires,
            'par_categorie' => $parCategorie,
        ]);
    }

    private function format(Announcement $a): array
    {
        return [
            'id'          => $a->id,
            'slug'        => $a->slug,
            'title'       => $a->title,
            'price'       => $a->price,
            'images'      => $a->images ?? [],
            'condition'   => $a->condition,
            'marque'      => $a->marque,
            'ville'       => $a->ville,
            'livraison'   => $a->livraison,
            'views'       => $a->views,
            'category'    => $a->category
                ? ['id' => $a->category->id, 'name' => $a->category->name, 'slug' => $a->category->slug]
                : null,
            'fournisseur' => [
                'id'             => $a->fournisseur_id,
                'nom_entreprise' => $a->fournisseur?->nom_entreprise,
            ],
            'created_at'  => $a->created_at->diffForHumans(),
        ];
    }
}