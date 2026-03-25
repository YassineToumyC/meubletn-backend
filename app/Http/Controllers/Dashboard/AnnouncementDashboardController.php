<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementDashboardController extends Controller
{
    /**
     * GET /api/dashboard/announcements
     */
    public function index(Request $request): JsonResponse
    {
        $fId = $this->fournisseurId($request);

        $announcements = Announcement::with(['category', 'subcategory'])
            ->where('fournisseur_id', $fId)
            ->latest()
            ->get()
            ->map(fn ($a) => $this->format($a));

        return response()->json($announcements);
    }

    /**
     * POST /api/dashboard/announcements
     * Accepts multipart/form-data with image files
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'price'          => ['nullable', 'numeric', 'min:0', 'max:999999.999'],
            'condition'      => ['required', 'in:new,like_new,used'],
            'marque'         => ['nullable', 'string', 'max:100'],
            'dimensions'     => ['nullable', 'string', 'max:150'],
            'ville'          => ['nullable', 'string', 'max:100'],
            'livraison'      => ['boolean'],
            'category_id'    => ['nullable', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'is_active'      => ['boolean'],
            'images'         => ['nullable', 'array', 'max:8'],
            'images.*'       => ['image', 'mimes:jpeg,png,webp,jpg', 'max:5120'],
        ]);

        $imagePaths = $this->handleImageUploads($request);

        $announcement = Announcement::create([
            'fournisseur_id' => $this->fournisseurId($request),
            'agent_id'       => $this->agentId($request),
            'title'          => $validated['title'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'] ?? null,
            'condition'      => $validated['condition'],
            'marque'         => $validated['marque'] ?? null,
            'dimensions'     => $validated['dimensions'] ?? null,
            'ville'          => $validated['ville'] ?? null,
            'livraison'      => $validated['livraison'] ?? false,
            'category_id'    => $validated['category_id'] ?? null,
            'subcategory_id' => $validated['subcategory_id'] ?? null,
            'is_active'      => $validated['is_active'] ?? true,
            'images'         => $imagePaths,
        ]);

        return response()->json($this->format($announcement), 201);
    }

    /**
     * GET /api/dashboard/announcements/{id}
     */
    public function show(Request $request, Announcement $announcement): JsonResponse
    {
        $this->authorizeAnnouncement($request, $announcement);

        return response()->json($this->format($announcement));
    }

    /**
     * PUT /api/dashboard/announcements/{id}
     * Handles new image uploads + deletion of removed images
     */
    public function update(Request $request, Announcement $announcement): JsonResponse
    {
        $this->authorizeAnnouncement($request, $announcement);

        $validated = $request->validate([
            'title'          => ['sometimes', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'price'          => ['nullable', 'numeric', 'min:0', 'max:999999.999'],
            'condition'      => ['sometimes', 'in:new,like_new,used'],
            'marque'         => ['nullable', 'string', 'max:100'],
            'dimensions'     => ['nullable', 'string', 'max:150'],
            'ville'          => ['nullable', 'string', 'max:100'],
            'livraison'      => ['boolean'],
            'category_id'    => ['nullable', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'is_active'      => ['boolean'],
            'images'         => ['nullable', 'array', 'max:8'],
            'images.*'       => ['image', 'mimes:jpeg,png,webp,jpg', 'max:5120'],
            'keep_images'    => ['nullable', 'array'],
            'keep_images.*'  => ['string'],
        ]);

        // Determine which existing images to keep / delete
        $keepImages    = $validated['keep_images'] ?? ($announcement->images ?? []);
        $deletedImages = array_diff($announcement->images ?? [], $keepImages);
        foreach ($deletedImages as $path) {
            Storage::disk('public')->delete($path);
        }

        $newImages  = $this->handleImageUploads($request);
        $allImages  = array_values(array_merge($keepImages, $newImages));

        $announcement->update([
            'title'          => $validated['title']          ?? $announcement->title,
            'description'    => array_key_exists('description', $validated)
                                    ? $validated['description']
                                    : $announcement->description,
            'price'          => array_key_exists('price', $validated)
                                    ? $validated['price']
                                    : $announcement->price,
            'condition'      => $validated['condition']      ?? $announcement->condition,
            'marque'         => array_key_exists('marque', $validated)
                                    ? $validated['marque']
                                    : $announcement->marque,
            'dimensions'     => array_key_exists('dimensions', $validated)
                                    ? $validated['dimensions']
                                    : $announcement->dimensions,
            'ville'          => array_key_exists('ville', $validated)
                                    ? $validated['ville']
                                    : $announcement->ville,
            'livraison'      => $validated['livraison']      ?? $announcement->livraison,
            'category_id'    => array_key_exists('category_id', $validated)
                                    ? $validated['category_id']
                                    : $announcement->category_id,
            'subcategory_id' => array_key_exists('subcategory_id', $validated)
                                    ? $validated['subcategory_id']
                                    : $announcement->subcategory_id,
            'is_active'      => $validated['is_active']     ?? $announcement->is_active,
            'images'         => $allImages,
        ]);

        return response()->json($this->format($announcement->fresh()));
    }

    /**
     * DELETE /api/dashboard/announcements/{id}
     */
    public function destroy(Request $request, Announcement $announcement): JsonResponse
    {
        $this->authorizeAnnouncement($request, $announcement);

        foreach ($announcement->images ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }

        $announcement->delete();

        return response()->json(['message' => 'Annonce supprimée.']);
    }

    // ─────────────────────────────────────────────────────────────

    private function fournisseurId(Request $request): int
    {
        $agent = $request->user('agent');
        if ($agent instanceof Agent) {
            return $agent->fournisseur_id;
        }
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

    private function authorizeAnnouncement(Request $request, Announcement $announcement): void
    {
        abort_if($announcement->fournisseur_id !== $this->fournisseurId($request), 403, 'Accès interdit.');
    }

    private function handleImageUploads(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('announcements', 'public');
            }
        }
        return $paths;
    }

    private function format(Announcement $a): array
    {
        return [
            'id'             => $a->id,
            'slug'           => $a->slug,
            'title'          => $a->title,
            'description'    => $a->description,
            'price'          => $a->price,
            'images'         => $a->images ?? [],
            'condition'      => $a->condition,
            'marque'         => $a->marque,
            'dimensions'     => $a->dimensions,
            'ville'          => $a->ville,
            'livraison'      => $a->livraison,
            'is_active'      => $a->is_active,
            'views'          => $a->views,
            'category_id'    => $a->category_id,
            'subcategory_id' => $a->subcategory_id,
            'category'       => $a->category
                ? ['id' => $a->category->id, 'name' => $a->category->name]
                : null,
            'subcategory'    => $a->subcategory
                ? ['id' => $a->subcategory->id, 'name' => $a->subcategory->name]
                : null,
            'fournisseur_id' => $a->fournisseur_id,
            'agent_id'       => $a->agent_id,
            'created_at'     => $a->created_at->format('Y-m-d'),
        ];
    }
}