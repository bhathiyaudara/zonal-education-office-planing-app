<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pirivena extends Model
{
    protected $table = 'pirivena';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'code', 'name', 'teachersCount', 'layCount', 'monkCount',
        'totalStudents', 'academicYear',
    ];

    protected $casts = [
        'teachersCount' => 'integer',
        'layCount' => 'integer',
        'monkCount' => 'integer',
        'totalStudents' => 'integer',
    ];

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
