<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTheme extends Model
{
    protected $fillable = [
        'name', 'slug', 'month', 'primary_color', 'secondary_color',
        'accent_color', 'text_color', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'month'  => 'integer',
    ];

    public static function getActive()
    {
        return static::where('active', true)->first();
    }

    public static function deactivateAll()
    {
        static::where('active', true)->update(['active' => false]);
    }
}
