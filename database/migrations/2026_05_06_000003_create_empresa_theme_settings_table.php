<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresa_theme_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('theme_id')->nullable()->constrained('themes')->nullOnDelete();
            
            // Custom color overrides (nullable - null means use theme or default)
            $table->string('color_primary')->nullable();
            $table->string('color_secondary')->nullable();
            $table->string('color_accent')->nullable();
            $table->string('color_text')->nullable();
            $table->string('color_text_light')->nullable();
            
            // Preferences
            $table->boolean('is_dark_mode_default')->default(false);
            $table->boolean('allow_theme_toggle')->default(true);
            $table->boolean('show_ceo_logo')->default(true);
            
            $table->timestamps();
            
            // Unique constraint: one theme setting per empresa
            $table->unique('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_theme_settings');
    }
};
