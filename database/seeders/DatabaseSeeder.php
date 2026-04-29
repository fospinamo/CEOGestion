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
     * 2. Catálogo de categorías (Categoria) - requerido por TipoEquipo
     * 3. Catálogo de tipos (TipoEquipo)
     * 4. Clientes (depende de Empresa, Municipio)
     * 5. Contratos (depende de Cliente, User)
     * 6. Usuarios (si no existen)
     * 7. Áreas (depende de Sede)
     * 8. Equipos (depende de Area, TipoEquipo)
     * 9. Servicios (depende de Equipo, Contrato)
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
        // 2. EMPRESA
        // =======================
        $this->call(EmpresaSeeder::class);

        // =======================
        // 3. CATEGORÍAS
        // =======================
        $this->call(CategoriaSeeder::class);

        // =======================
        // 4. CATÁLOGO TI
        // =======================
        $this->call(TipoEquipoSeeder::class);

        // =======================
        // 5. CLIENTES Y CONTRATOS
        // =======================
        $this->call([
            ClienteSeeder::class,
            ContratoSeeder::class,
            ContratoServicioSeeder::class,
        ]);

        // =======================
        // 6. USUARIOS CON ROLES
        // =======================
        // Crear después de clientes para que cada cliente tenga su usuario de acceso
        $this->call(UsuariosConRolesSeeder::class);

        // =======================
        // 7. SEDES DE CLIENTES
        // =======================
        $this->call(SedeSeeder::class);

        // =======================
        // 8. INFRAESTRUCTURA TI
        // =======================
        $this->call([
            AreaSeeder::class,
            EquipoSeeder::class,
            ServicioSeeder::class,
        ]);
    }
}

