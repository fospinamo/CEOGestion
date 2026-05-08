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
        Schema::create('themes', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED (compatible con empresas.id)
            $table->string('name')->unique(); // slug: corporate-blue
            $table->string('label'); // display name: Corporativo Azul
            $table->text('description')->nullable();
            
            // Color fields
            $table->string('color_primary')->default('#0066CC');
            $table->string('color_secondary')->default('#F5F5F5');
            $table->string('color_accent')->default('#00AA88');
            $table->string('color_text')->default('#1A1A1A');
            $table->string('color_text_light')->default('#FFFFFF');
            
            // Background
            $table->string('bg_dark')->default('#0D2A54');
            $table->string('bg_light')->default('#FFFFFF');
            
            // Flags
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
