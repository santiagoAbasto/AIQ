<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Logincliente extends Authenticatable
{
    use HasFactory, Notifiable;

    public const INACTIVE_RETENTION_DAYS = 30;

    protected $table = 'loginclientes';

    protected $fillable = [
        'name',
        'email',
        'company',
        'phone',
        'password',
        'is_enabled',
        'access_unlimited',
        'access_starts_at',
        'access_expires_at',
        'approved_at',
        'inactive_since_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_enabled' => 'boolean',
        'access_unlimited' => 'boolean',
        'access_starts_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'inactive_since_at' => 'datetime',
    ];

    public function importedClientes()
    {
        return $this->hasMany(ClienteImportado::class, 'logincliente_id');
    }

    public function aiRequests()
    {
        return $this->hasMany(ClienteAiRequest::class, 'logincliente_id');
    }

    public function aiChats()
    {
        return $this->hasMany(ClienteAiChat::class, 'logincliente_id');
    }

    public function hasValidAccess(): bool
    {
        if (! $this->is_enabled) {
            return false;
        }

        if ($this->access_starts_at && $this->access_starts_at->isFuture()) {
            return false;
        }

        if ($this->access_unlimited) {
            return true;
        }

        return $this->access_expires_at && $this->access_expires_at->endOfDay()->isFuture();
    }

    public function getAccessStatusAttribute(): string
    {
        if (! $this->is_enabled) {
            return 'Pendiente o deshabilitado';
        }

        if ($this->access_unlimited) {
            return 'Ilimitado';
        }

        if ($this->access_expires_at && $this->access_expires_at->endOfDay()->isFuture()) {
            return 'Vigente hasta '.$this->access_expires_at->format('d/m/Y');
        }

        return 'Vencido';
    }

    public function getInactiveDaysAttribute(): int
    {
        if (! $this->inactive_since_at) {
            return 0;
        }

        return (int) floor($this->inactive_since_at->diffInDays(now()));
    }

    public function getDaysUntilDeletionAttribute(): int
    {
        return max(0, self::INACTIVE_RETENTION_DAYS - $this->inactive_days);
    }
}
