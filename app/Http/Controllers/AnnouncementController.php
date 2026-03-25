<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * GET /api/announcements
     * Public listing — paginated, filterable by category & search
     */
    public function index(Request $request): JsonResponse
    {
        $query = Announcement::with(['category', 'subcategory', 'fournisseur.user'])
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Sorting
        match ($request->input('sort')) {
            'popular'    => $query->orderByDesc('views'),
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default      => $query->latest(),
        };

        $perPage = min((int) $request->input('per_page', 20), 50);
        $page = $query->paginate($perPage);

        return response()->json([
            'data'         => collect($page->items())->map(fn ($a) => $this->format($a)),
            'current_page' => $page->currentPage(),
            'last_page'    => $page->lastPage(),
            'total'        => $page->total(),
        ]);
    }

    /**
     * GET /api/announcements/{slug}
     * Single product view — increments views counter
     */
    public function show(string $slug): JsonResponse
    {
        $announcement = Announcement::with(['category', 'subcategory', 'fournisseur.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $announcement->increment('views');

        return response()->json($this->format($announcement, true));
    }

    // ─────────────────────────────────────────────────────────────

    private function format(Announcement $a, bool $full = false): array
    {
        $data = [
            'id'          => $a->id,
            'slug'        => $a->slug,
            'title'       => $a->title,
            'price'       => $a->price,
            'images'      => $a->images ?? [],
            'condition'   => $a->condition,
            'marque'      => $a->marque,
            'dimensions'  => $a->dimensions,
            'ville'       => $a->ville,
            'livraison'   => $a->livraison,
            'views'       => $a->views,
            'category'    => $a->category
                ? ['id' => $a->category->id, 'name' => $a->category->name, 'slug' => $a->category->slug]
                : null,
            'subcategory' => $a->subcategory
                ? ['id' => $a->subcategory->id, 'name' => $a->subcategory->name]
                : null,
            'fournisseur' => [
                'id'             => $a->fournisseur_id,
                'nom_entreprise' => $a->fournisseur?->nom_entreprise,
            ],
            'created_at'  => $a->created_at->diffForHumans(),
        ];

        if ($full) {
            $data['description'] = $a->description;
        } else {
            // Truncate description for list view
            $data['description'] = $a->description ? mb_substr($a->description, 0, 120) : null;
        }

        return $data;
    }
}