<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('marque', 100)->nullable()->after('condition');
            $table->string('dimensions', 150)->nullable()->after('marque');
            $table->string('ville', 100)->nullable()->after('dimensions');
            $table->boolean('livraison')->default(false)->after('ville');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['marque', 'dimensions', 'ville', 'livraison']);
        });
    }
};