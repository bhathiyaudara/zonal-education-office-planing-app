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
        'contactNumber', 'email', 'schoolType', 'eduZone', 'eduDivision',
        'gradeStart', 'gradeEnd', 'sanctionedPositions', 'principalName',
        'principalNic', 'principalMobile', 'isActive',
    ];

    protected $casts = [
        'gradeStart' => 'integer',
        'gradeEnd' => 'integer',
        'sanctionedPositions' => 'integer',
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
