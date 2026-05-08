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
     * 2. Empresa
     * 3. Roles y Permisos (CRÍTICO - Debe ser primero antes de usuarios)
     * 4. Catálogo de categorías (Categoria) - requerido por TipoEquipo
     * 5. Catálogo de tipos (TipoEquipo)
     * 6. Clientes (depende de Empresa, Municipio)
     * 7. Contratos (depende de Cliente, User)
     * 8. Usuarios (con roles y permisos asignados)
     * 9. Áreas (depende de Sede)
     * 10. Equipos (depende de Area, TipoEquipo)
     * 11. Servicios (depende de Equipo, Contrato)
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
        // 3. ROLES Y PERMISOS (CRÍTICO - Debe ser primero)
        // =======================
        $this->call(RoleAndPermissionSeeder::class);

        // =======================
        // 4. TEMAS (TEMA DEL LOGIN Y APLICACIÓN)
        // =======================
        $this->call(ThemeSeeder::class);

        // =======================
        // 5. CATEGORÍAS
        // =======================
        $this->call(CategoriaSeeder::class);

        // =======================
        // 5. CATEGORÍAS
        // =======================
        $this->call(CategoriaSeeder::class);

        // =======================
        // 6. CATÁLOGO TI
        // =======================
        $this->call(TipoEquipoSeeder::class);

        // =======================
        // 7. CLIENTES Y CONTRATOS
        // =======================
        $this->call([
            ClienteSeeder::class,
            ContratoSeeder::class,
            ContratoServicioSeeder::class,
        ]);

        // =======================
        // 8. USUARIOS CON ROLES
        // =======================
        // Crear después de clientes para que cada cliente tenga su usuario de acceso
        $this->call(UsuariosConRolesSeeder::class);

        // =======================
        // 9. SEDES DE CLIENTES
        // =======================
        $this->call(SedeSeeder::class);

        // =======================
        // 10. INFRAESTRUCTURA TI
        // =======================
        $this->call([
            AreaSeeder::class,
            EquipoSeeder::class,
            ServicioSeeder::class,
        ]);
    }
}

