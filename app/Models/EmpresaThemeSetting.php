<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaThemeSetting extends Model
{
    protected $table = 'empresa_theme_settings';

    protected $fillable = [
        'empresa_id',
        'theme_id',
        'color_primary',
        'color_secondary',
        'color_accent',
        'color_text',
        'color_text_light',
        'is_dark_mode_default',
        'allow_theme_toggle',
        'show_ceo_logo',
    ];

    protected $casts = [
        'is_dark_mode_default' => 'boolean',
        'allow_theme_toggle' => 'boolean',
        'show_ceo_logo' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    // Métodos para obtener colores con fallback
    public function getPrimaryColor()
    {
        return $this->color_primary ?? ($this->theme?->color_primary ?? '#0066CC');
    }

    public function getSecondaryColor()
    {
        return $this->color_secondary ?? ($this->theme?->color_secondary ?? '#F5F5F5');
    }

    public function getAccentColor()
    {
        return $this->color_accent ?? ($this->theme?->color_accent ?? '#00AA88');
    }

    public function getTextColor()
    {
        return $this->color_text ?? ($this->theme?->color_text ?? '#1A1A1A');
    }

    public function getTextLightColor()
    {
        return $this->color_text_light ?? ($this->theme?->color_text_light ?? '#FFFFFF');
    }
}
