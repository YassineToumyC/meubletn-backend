<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Announcement extends Model
{
    protected $fillable = [
        'fournisseur_id',
        'agent_id',
        'category_id',
        'subcategory_id',
        'title',
        'slug',
        'description',
        'price',
        'images',
        'condition',
        'marque',
        'dimensions',
        'ville',
        'livraison',
        'is_active',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'images'    => 'array',
            'is_active' => 'boolean',
            'livraison' => 'boolean',
            'price'     => 'decimal:3',
            'views'     => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Announcement $a) {
            if (empty($a->slug)) {
                $base = Str::slug($a->title);
                $slug = $base;
                $i    = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $a->slug = $slug;
            }
        });
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }
}