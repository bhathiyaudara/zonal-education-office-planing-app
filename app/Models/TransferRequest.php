<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransferRequest extends Model
{
    protected $table = 'transferrequest';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'teacherId', 'fromSchoolId', 'toSchoolId',
        'reason', 'status', 'decidedBy', 'decidedAt',
    ];

    protected $casts = [
        'decidedAt' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacherId');
    }

    public function fromSchool()
    {
        return $this->belongsTo(School::class, 'fromSchoolId');
    }

    public function toSchool()
    {
        return $this->belongsTo(School::class, 'toSchoolId');
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
