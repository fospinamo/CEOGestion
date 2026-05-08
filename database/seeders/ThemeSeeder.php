<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 5 Predefined themes
        $themes = [
            [
                'name' => 'corporate-blue',
                'label' => 'Corporativo Azul',
                'description' => 'Professional blue theme for banking and corporate environments',
                'color_primary' => '#0066CC',
                'color_secondary' => '#F5F5F5',
                'color_accent' => '#00AA88',
                'color_text' => '#1A1A1A',
                'color_text_light' => '#FFFFFF',
                'bg_dark' => '#0D2A54',
                'bg_light' => '#FFFFFF',
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'elegant-black',
                'label' => 'Elegante Negro',
                'description' => 'Luxury black theme with gold accents',
                'color_primary' => '#1A1A1A',
                'color_secondary' => '#EFEFEF',
                'color_accent' => '#FFD700',
                'color_text' => '#333333',
                'color_text_light' => '#FFFFFF',
                'bg_dark' => '#0F0F0F',
                'bg_light' => '#FAFAFA',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'modern-green',
                'label' => 'Moderno Verde',
                'description' => 'Fresh green theme for tech and startups',
                'color_primary' => '#10B981',
                'color_secondary' => '#F0FDF4',
                'color_accent' => '#8B5CF6',
                'color_text' => '#064E3B',
                'color_text_light' => '#FFFFFF',
                'bg_dark' => '#065F46',
                'bg_light' => '#ECFDF5',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'tech-dark',
                'label' => 'Tech Oscuro',
                'description' => 'Dark cyberpunk theme for tech companies',
                'color_primary' => '#0F172A',
                'color_secondary' => '#1E293B',
                'color_accent' => '#06B6D4',
                'color_text' => '#E2E8F0',
                'color_text_light' => '#FFFFFF',
                'bg_dark' => '#020617',
                'bg_light' => '#F1F5F9',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'warm-orange',
                'label' => 'Cálido Naranja',
                'description' => 'Warm orange theme for dynamic brands',
                'color_primary' => '#EA580C',
                'color_secondary' => '#FEF3C7',
                'color_accent' => '#F59E0B',
                'color_text' => '#92400E',
                'color_text_light' => '#FFFFFF',
                'bg_dark' => '#78350F',
                'bg_light' => '#FFFBEB',
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::firstOrCreate(
                ['name' => $theme['name']],
                $theme
            );
        }
    }
}
