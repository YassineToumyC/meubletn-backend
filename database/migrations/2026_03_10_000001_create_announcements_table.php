<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['promo', 'brand', 'category', 'seasonal', 'info'])->default('promo');
            $table->string('tag', 60);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cta', 80)->nullable();
            $table->string('href', 255)->nullable();
            $table->string('bg_class', 60)->default('annonce--red');
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};