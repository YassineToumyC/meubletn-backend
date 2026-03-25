<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('fournisseur_id')->nullable()->after('subcategory_id')->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->after('fournisseur_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Fournisseur::class);
            $table->dropForeignIdFor(\App\Models\Agent::class);
        });
    }
};