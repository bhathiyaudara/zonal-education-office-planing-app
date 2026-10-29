<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'name', 'email', 'password', 'role', 'schoolId', 'isActive', 'image',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'isActive' => 'boolean',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    // Roles
    const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';
    const ROLE_ZONAL_ADMIN = 'ZONAL_ADMIN';
    const ROLE_SCHOOL_ADMIN = 'SCHOOL_ADMIN';
    const ROLE_TEACHER = 'TEACHER';

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isZonalAdmin(): bool
    {
        return $this->role === self::ROLE_ZONAL_ADMIN || $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isSchoolAdmin(): bool
    {
        return $this->role === self::ROLE_SCHOOL_ADMIN || $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Administrator',
            self::ROLE_ZONAL_ADMIN => 'Zonal Administrator',
            self::ROLE_SCHOOL_ADMIN => 'School Administrator',
            self::ROLE_TEACHER => 'Teacher',
            default => $this->role,
        };
    }

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }

    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class, 'userId');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'userId');
    }

    // Boot to auto-generate CUID-like IDs
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::ulid();
            }
        });
    }
}
