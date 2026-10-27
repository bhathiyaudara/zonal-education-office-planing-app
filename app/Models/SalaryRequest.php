<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SalaryRequest extends Model
{
    protected $table = 'salaryrequest';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'teacherId', 'schoolId', 'documentType', 'periodType',
        'periodDetails', 'status', 'remarks', 'requestDate',
        'processedDate', 'processedBy',
    ];

    protected $casts = [
        'requestDate' => 'datetime',
        'processedDate' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacherId');
    }

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
