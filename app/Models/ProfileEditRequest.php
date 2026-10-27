<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProfileEditRequest extends Model
{
    protected $table = 'profileeditrequest';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'teacherId', 'schoolId', 'fieldName', 'fieldLabel',
        'currentValue', 'requestedValue', 'reason', 'status',
        'adminRemarks', 'processedBy', 'processedDate',
    ];

    protected $casts = [
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
