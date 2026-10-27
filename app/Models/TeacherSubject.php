<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeacherSubject extends Model
{
    protected $table = 'teachersubject';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    const CREATED_AT = 'createdAt';

    protected $fillable = ['id', 'teacherId', 'subjectId'];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacherId');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjectId');
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
