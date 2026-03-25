<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    protected $fillable = [
        'fournisseur_id',
        'agent_id',
        'category_id',
        'subcategory_id',
        'titre',
        'description',
        'prix',
        'prix_barre',
        'ville',
        'images',
        'statut',
        'vues',
    ];

    protected function casts(): array
    {
        return [
            'images'     => 'array',
            'prix'       => 'float',
            'prix_barre' => 'float',
        ];
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
