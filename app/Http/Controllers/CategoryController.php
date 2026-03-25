<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('subcategories')
            ->where('is_active', true)
            ->orderBy('position')
            ->get()
            ->map(fn(Category $cat) => [
                'id'       => $cat->id,
                'name'     => $cat->name,
                'slug'     => $cat->slug,
                'icon'     => $cat->icon,
                'position' => $cat->position,
                'subcategories' => $cat->subcategories
                    ->where('is_active', true)
                    ->map(fn($sub) => [
                        'id'       => $sub->id,
                        'name'     => $sub->name,
                        'slug'     => $sub->slug,
                        'position' => $sub->position,
                    ])
                    ->values(),
            ]);

        return response()->json($categories);
    }
}
