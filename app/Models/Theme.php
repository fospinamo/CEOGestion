<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'color_primary',
        'color_secondary',
        'color_accent',
        'color_text',
        'color_text_light',
        'bg_dark',
        'bg_light',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function empresas()
    {
        return $this->hasMany(EmpresaThemeSetting::class);
    }
}
