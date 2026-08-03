<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiIntegrationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'value',
        'is_secret',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'encrypted',
        'is_secret' => 'boolean',
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function valueFor(string $key, ?string $default = null): ?string
    {
        $setting = static::query()->where('setting_key', $key)->first();

        if (! $setting || blank($setting->value)) {
            return $default;
        }

        return $setting->value;
    }

    public static function hasStoredValue(string $key): bool
    {
        $setting = static::query()->where('setting_key', $key)->first();

        return (bool) $setting && filled($setting->value);
    }

    public static function putValue(string $key, ?string $value, bool $isSecret = true, ?int $userId = null): void
    {
        static::query()->updateOrCreate(
            ['setting_key' => $key],
            [
                'value' => $value,
                'is_secret' => $isSecret,
                'updated_by' => $userId,
            ]
        );
    }

    public static function forgetKey(string $key): void
    {
        static::query()->where('setting_key', $key)->delete();
    }
}
