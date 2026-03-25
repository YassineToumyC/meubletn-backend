<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old banner-style announcements table
        Schema::dropIfExists('announcements');

        // Recreate as product/listing table
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 3)->nullable();          // Tunisian Dinar (3 decimal places)
            $table->json('images')->nullable();                    // array of storage paths
            $table->enum('condition', ['new', 'like_new', 'used'])->default('new');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('views')->default(0);

            $table->timestamps();
        });

        // Drop listings table — no longer needed
        Schema::dropIfExists('listings');
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('listings');
    }
};