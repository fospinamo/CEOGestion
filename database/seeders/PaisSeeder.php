<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pais::firstOrCreate(
            ['codigo_dane' => '170'],
            ['nombre' => 'Colombia']
        );
    }
}
