<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Teacher extends Model
{
    protected $table = 'teacher';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'userId', 'schoolId', 'nic', 'nameWithInitials', 'fullName',
        'dateOfBirth', 'gender', 'address', 'phone', 'whatsapp', 'email',
        'maritalStatus', 'spouseIsTeacherInWilgamuwa', 'spouseNameWithInitials',
        'spouseNic', 'spouseSchoolId', 'appointmentDate', 'appointmentType',
        'appointmentMedium', 'serviceGrade', 'serviceGradeAppointedDate',
        'currentPosition', 'joinedCurrentSchoolDate', 'isLockedByZonal',
        'isLockedBySchool', 'fileNumber', 'salaryNumber', 'appointmentSubject',
        'trainingStatus', 'natureOfAppointment', 'salaryPayingSchool',
        'undergraduateDegrees', 'undergraduateDegreesOther', 'postgraduateDiplomas',
        'postgraduateDiplomasOther', 'postgraduateDegrees', 'postgraduateDegreesOther',
        'additionalTeachingQualifications', 'pastServiceDetails', 'appointmentLetterUrl',
        'createdByUserId', 'updatedByUserId',
    ];

    protected $casts = [
        'dateOfBirth' => 'datetime',
        'appointmentDate' => 'datetime',
        'serviceGradeAppointedDate' => 'datetime',
        'joinedCurrentSchoolDate' => 'datetime',
        'spouseIsTeacherInWilgamuwa' => 'boolean',
        'isLockedByZonal' => 'boolean',
        'isLockedBySchool' => 'boolean',
        'undergraduateDegrees' => 'array',
        'postgraduateDiplomas' => 'array',
        'postgraduateDegrees' => 'array',
        'additionalTeachingQualifications' => 'array',
        'pastServiceDetails' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }

    public function spouseSchool()
    {
        return $this->belongsTo(School::class, 'spouseSchoolId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createdByUserId');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updatedByUserId');
    }

    public function subjects()
    {
        return $this->hasMany(TeacherSubject::class, 'teacherId');
    }

    public function subjectNames()
    {
        return $this->hasManyThrough(Subject::class, TeacherSubject::class, 'teacherId', 'id', 'id', 'subjectId');
    }

    public function editRequests()
    {
        return $this->hasMany(ProfileEditRequest::class, 'teacherId');
    }

    public function transferRequests()
    {
        return $this->hasMany(TransferRequest::class, 'teacherId');
    }

    public function salaryRequests()
    {
        return $this->hasMany(SalaryRequest::class, 'teacherId');
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
