<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * 
     * Orden de ejecución respetando dependencias de foreign keys:
     * 1. Ubicación DANE (Pais → Departamento → Municipio → Barrio)
     * 2. Catálogo de tipos (TipoEquipo)
     * 3. Clientes (depende de Empresa, Municipio)
     * 4. Contratos (depende de Cliente, User)
     * 5. Usuarios (si no existen)
     * 6. Áreas (depende de Sede)
     * 7. Equipos (depende de Area, TipoEquipo)
     * 8. Servicios (depende de Equipo, Contrato)
     */
    public function run(): void
    {
        // =======================
        // 1. UBICACIÓN DANE
        // =======================
        $this->call([
            PaisSeeder::class,
            DepartamentoSeeder::class,
            MunicipioSeeder::class,
            BarrioSeeder::class,
        ]);

        // =======================
        // 2. CATÁLOGO TI
        // =======================
        $this->call(TipoEquipoSeeder::class);

        // =======================
        // 3. CLIENTES Y CONTRATOS
        // =======================
        $this->call([
            ClienteSeeder::class,
            ContratoSeeder::class,
            ContratoServicioSeeder::class,
        ]);

        // =======================
        // 4. USUARIOS CON ROLES
        // =======================
        // Crear después de clientes para que cada cliente tenga su usuario de acceso
        $this->call(UsuariosConRolesSeeder::class);

        // =======================
        // 5. INFRAESTRUCTURA TI
        // =======================
        $this->call([
            AreaSeeder::class,
            EquipoSeeder::class,
            ServicioSeeder::class,
        ]);
    }
}

