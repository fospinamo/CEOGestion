<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'rol',
                'remember_token',
                'email_verified_at',
                'permisos',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'administrativo', 'conductor', 'pasajero'])->default('pasajero')->after('sede_id');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->json('permisos')->nullable();
        });
    }
};
