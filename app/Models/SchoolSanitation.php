<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSanitation extends Model
{
    protected $table = 'school_sanitation';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'schoolId',
        'boysToilets',
        'girlsToilets',
        'teacherToilets',
    ];

    protected $casts = [
        'boysToilets' => 'integer',
        'girlsToilets' => 'integer',
        'teacherToilets' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }

    public function getTotalToiletsAttribute(): int
    {
        return (int)$this->boysToilets + (int)$this->girlsToilets + (int)$this->teacherToilets;
    }
}
