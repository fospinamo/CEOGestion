<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subprocesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proceso_id')->constrained('procesos')->cascadeOnDelete();
            $table->string('nombre', 150);
            $table->string('ruta', 255);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::table('procesos', function (Blueprint $table) {
            if (Schema::hasColumn('procesos', 'subproceso')) {
                $table->dropColumn('subproceso');
            }
            if (Schema::hasColumn('procesos', 'ruta')) {
                $table->dropColumn('ruta');
            }
        });
    }

    public function down(): void
    {
        Schema::table('procesos', function (Blueprint $table) {
            if (!Schema::hasColumn('procesos', 'subproceso')) {
                $table->string('subproceso', 150)->nullable();
            }
            if (!Schema::hasColumn('procesos', 'ruta')) {
                $table->string('ruta', 255)->nullable();
            }
        });

        Schema::dropIfExists('subprocesos');
    }
};
