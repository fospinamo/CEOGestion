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
        Schema::table('empresas', function (Blueprint $table) {
            // Eliminar el campo ruc existente
            $table->dropUnique(['ruc']);
            $table->dropColumn('ruc');
            
            // Agregar nuevos campos de NIT
            $table->string('nit')->unique()->after('nombre')->index();
            $table->string('digito_verificacion', 1)->after('nit');
            
            // Agregar otros campos
            $table->string('pagina_web')->nullable()->after('email');
            $table->string('tipo_contribuyente')->default('persona_juridica')->after('pagina_web');
            $table->json('responsabilidades_fiscales')->nullable()->after('tipo_contribuyente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropIndex(['nit']);
            $table->dropColumn(['nit', 'digito_verificacion', 'pagina_web', 'tipo_contribuyente', 'responsabilidades_fiscales']);
            $table->string('ruc')->unique()->after('nombre');
        });
    }
};
