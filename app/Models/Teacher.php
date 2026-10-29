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
        'id', 'userId', 'schoolId', 'census_no', 'nic', 'paysheet', 'title',
        'personal_file_no', 'nameWithInitials', 'fullName',
        'dateOfBirth', 'gender', 'ethnicity', 'ethnicity_other', 'religion',
        'religion_other', 'address', 'phone', 'whatsapp', 'email',
        'maritalStatus', 'marital_other', 'eduqua', 'subject_stream',
        'field_study', 'field_study_other', 'profqua', 'profqua_other',
        'profall', 'service', 'firstappoint', 'class_grade', 'clsappoint',
        'schappoint', 'appt_section', 'appt_category', 'appt_code',
        'appt_medium', 'mainduty', 'mainduty_other', 'secondduty',
        'secondduty_other', 't1_section', 't1_category', 't1_code',
        't1_medium', 't1_periods', 't2_section', 't2_category', 't2_code',
        't2_medium', 't2_periods', 'able1_section', 'able1_category',
        'able1_code', 'able1_medium', 'able2_section', 'able2_category',
        'able2_code', 'able2_medium', 'otherschool', 'otherschool_census',
        'otherschool_days', 'position', 'salaryby', 'servicebased',
        'salarysource', 'spouseIsTeacherInWilgamuwa', 'spouseNameWithInitials',
        'spouseNic', 'spouseSchoolId', 'appointmentDate', 'appointmentType',
        'appointmentMedium', 'serviceGrade', 'serviceGradeAppointedDate',
        'currentPosition', 'joinedCurrentSchoolDate', 'isLockedByZonal',
        'isLockedBySchool', 'fileNumber', 'salaryNumber', 'appointmentSubject',
        'trainingStatus', 'natureOfAppointment', 'salaryPayingSchool',
        'undergraduateDegrees', 'undergraduateDegreesOther', 'postgraduateDiplomas',
        'postgraduateDiplomasOther', 'postgraduateDegrees', 'postgraduateDegreesOther',
        'additionalTeachingQualifications', 'pastServiceDetails', 'appointmentLetterUrl',
        'form_data', 'createdByUserId', 'updatedByUserId',
    ];

    protected $casts = [
        'dateOfBirth' => 'datetime',
        'firstappoint' => 'datetime',
        'clsappoint' => 'datetime',
        'schappoint' => 'datetime',
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
        'form_data' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }

    public function otherSchool()
    {
        return $this->belongsTo(School::class, 'otherschool_census', 'censusNo');
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
