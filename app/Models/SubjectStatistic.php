<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubjectStatistic extends Model
{
    protected $table = 'subject_statistic';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'schoolId', 'schoolCensusNo', 'schoolName', 'level',
        'subject', 'grade', 'maleCount', 'femaleCount', 'totalCount',
        'academicYear',
    ];

    protected $casts = [
        'maleCount' => 'integer',
        'femaleCount' => 'integer',
        'totalCount' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolId');
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
