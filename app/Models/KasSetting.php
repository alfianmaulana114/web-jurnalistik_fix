<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KasSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
        'type',
    ];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("kas_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value, string $description = null, string $type = 'text')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
                'type' => $type,
            ]
        );

        // Clear cache
        Cache::forget("kas_setting_{$key}");

        return $setting;
    }

    /**
     * Alias for get method to maintain consistency
     */
    public static function getValue(string $key, $default = null)
    {
        return self::get($key, $default);
    }

    /**
     * Get jumlah kas anggota
     */
    public static function getJumlahKasAnggota(): int
    {
        return (int) self::get('jumlah_kas_anggota', 15000);
    }

    /**
     * Set jumlah kas anggota
     */
    public static function setJumlahKasAnggota(int $amount): void
    {
        self::set('jumlah_kas_anggota', $amount, 'Jumlah iuran kas anggota per periode', 'number');
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings(): array
    {
        return self::pluck('value', 'key')->toArray();
    }
}