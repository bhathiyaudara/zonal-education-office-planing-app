<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class School extends Model
{
    protected $table = 'school';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'name', 'nameSinhala', 'category', 'censusNo', 'address',
        'landArea', 'difficultyCategory',
        'contactNumber', 'email', 'schoolType', 'eduZone', 'eduDivision',
        'dsDivision', 'gnDivision', 'latitude', 'longitude',
        'gradeStart', 'gradeEnd', 'sanctionedPositions', 'principalName',
        'principalNic', 'principalMobile', 'isActive',
    ];

    protected $casts = [
        'gradeStart' => 'integer',
        'gradeEnd' => 'integer',
        'sanctionedPositions' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'isActive' => 'boolean',
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'schoolId');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'schoolId');
    }

    public function studentCounts()
    {
        return $this->hasMany(StudentCount::class, 'schoolId');
    }

    public function subjectStatistics()
    {
        return $this->hasMany(SubjectStatistic::class, 'schoolId');
    }

    public function transfersFrom()
    {
        return $this->hasMany(TransferRequest::class, 'fromSchoolId');
    }

    public function transfersTo()
    {
        return $this->hasMany(TransferRequest::class, 'toSchoolId');
    }

    public function buildings()
    {
        return $this->hasMany(Building::class, 'schoolId');
    }

    public function sanitation()
    {
        return $this->hasOne(SchoolSanitation::class, 'schoolId');
    }

    public function getBilingualDifficultyAttribute(): ?string
    {
        $d = trim($this->difficultyCategory ?? '');
        if (!$d) return null;

        if (stripos($d, 'Very Difficult') !== false || stripos($d, 'ඉතා දුෂ්කර') !== false) {
            return 'Very Difficult (ඉතා දුෂ්කර)';
        }
        if (stripos($d, 'Very Convenient') !== false || stripos($d, 'ඉතා පහසු') !== false) {
            return 'Very Convenient (ඉතා පහසු)';
        }
        if (stripos($d, 'Difficult') !== false || stripos($d, 'දුෂ්කර') !== false) {
            return 'Difficult (දුෂ්කර)';
        }
        if (stripos($d, 'Convenient') !== false || stripos($d, 'පහසු') !== false) {
            return 'Convenient (පහසු)';
        }
        return $d;
    }

    public function getDifficultyBadgeClassAttribute(): string
    {
        $d = trim($this->difficultyCategory ?? '');
        if (stripos($d, 'Very Difficult') !== false || stripos($d, 'ඉතා දුෂ්කර') !== false) {
            return 'bg-rose-50 text-rose-700 border-rose-200';
        }
        if (stripos($d, 'Very Convenient') !== false || stripos($d, 'ඉතා පහසු') !== false) {
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        }
        if (stripos($d, 'Difficult') !== false || stripos($d, 'දුෂ්කර') !== false) {
            return 'bg-amber-50 text-amber-700 border-amber-200';
        }
        if (stripos($d, 'Convenient') !== false || stripos($d, 'පහසු') !== false) {
            return 'bg-blue-50 text-blue-700 border-blue-200';
        }
        return 'bg-slate-50 text-slate-700 border-slate-200';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::ulid();
            }
        });
    }
}
