<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'schoolId',
        'buildingName',
        'buildingType',
        'size',
        'lengthFeet',
        'widthFeet',
        'areaSqft',
        'floors',
        'constructionYear',
        'lastRepairYear',
        'status',
        'notes',
    ];

    protected $casts = [
        'lengthFeet' => 'float',
        'widthFeet' => 'float',
        'areaSqft' => 'float',
        'floors' => 'integer',
        'constructionYear' => 'integer',
        'lastRepairYear' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }

    public function getYearsSinceRepairAttribute(): ?int
    {
        if (!$this->lastRepairYear) {
            return null;
        }
        return max(0, (int)date('Y') - $this->lastRepairYear);
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->constructionYear) {
            return null;
        }
        return max(0, (int)date('Y') - $this->constructionYear);
    }

    public function getNeedsRenovationAttribute(): bool
    {
        if (!$this->lastRepairYear) {
            return true;
        }
        return ((int)date('Y') - $this->lastRepairYear) >= 10;
    }
}
