<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StudentCount extends Model
{
    protected $table = 'studentcount';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'schoolId', 'grade', 'academicYear', 'maleCount',
        'femaleCount', 'classCount', 'classes', 'classDetails',
    ];

    protected $casts = [
        'maleCount' => 'integer',
        'femaleCount' => 'integer',
        'classCount' => 'integer',
        'classDetails' => 'array',
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
