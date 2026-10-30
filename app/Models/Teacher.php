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
        'personal_file_no', 'fullName',
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
        'salarysource',
        'form_data', 'createdByUserId', 'updatedByUserId',
    ];

    protected $casts = [
        'dateOfBirth' => 'datetime',
        'firstappoint' => 'datetime',
        'clsappoint' => 'datetime',
        'schappoint' => 'datetime',
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

    public function getAttachedInstitutionNameAttribute(): string
    {
        if (!$this->otherschool_census) {
            return '';
        }
        return \App\Services\TeacherOptions::institutionName($this->otherschool_census);
    }

    public function getOtherschoolAttribute($value)
    {
        if ($value && (str_starts_with($value, 'Yes — full time') || $value === 'Yes')) {
            return 'Yes — full time at another school/office/institute';
        }
        return $value;
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

    // Compatibility accessors for legacy code / views
    public function getNameWithInitialsAttribute(): ?string
    {
        return $this->fullName;
    }

    public function setNameWithInitialsAttribute($val): void
    {
        if (!empty($val) && empty($this->fullName)) {
            $this->attributes['fullName'] = $val;
        }
    }

    public function getCurrentPositionAttribute(): ?string
    {
        return $this->position;
    }

    public function setCurrentPositionAttribute($val): void
    {
        if (!empty($val) && empty($this->position)) {
            $this->attributes['position'] = $val;
        }
    }

    public function getAppointmentDateAttribute()
    {
        return $this->firstappoint;
    }

    public function setAppointmentDateAttribute($val): void
    {
        if (!empty($val) && empty($this->firstappoint)) {
            $this->attributes['firstappoint'] = $val;
        }
    }

    public function getServiceGradeAttribute(): ?string
    {
        return $this->class_grade;
    }

    public function setServiceGradeAttribute($val): void
    {
        if (!empty($val) && empty($this->class_grade)) {
            $this->attributes['class_grade'] = $val;
        }
    }

    public function getServiceGradeAppointedDateAttribute()
    {
        return $this->clsappoint;
    }

    public function setServiceGradeAppointedDateAttribute($val): void
    {
        if (!empty($val) && empty($this->clsappoint)) {
            $this->attributes['clsappoint'] = $val;
        }
    }

    public function getJoinedCurrentSchoolDateAttribute()
    {
        return $this->schappoint;
    }

    public function setJoinedCurrentSchoolDateAttribute($val): void
    {
        if (!empty($val) && empty($this->schappoint)) {
            $this->attributes['schappoint'] = $val;
        }
    }

    public function getFileNumberAttribute(): ?string
    {
        return $this->personal_file_no;
    }

    public function setFileNumberAttribute($val): void
    {
        if (!empty($val) && empty($this->personal_file_no)) {
            $this->attributes['personal_file_no'] = $val;
        }
    }

    public function getSalaryNumberAttribute(): ?string
    {
        return $this->paysheet;
    }

    public function setSalaryNumberAttribute($val): void
    {
        if (!empty($val) && empty($this->paysheet)) {
            $this->attributes['paysheet'] = $val;
        }
    }

    public function getAppointmentMediumAttribute(): ?string
    {
        return $this->appt_medium;
    }

    public function setAppointmentMediumAttribute($val): void
    {
        if (!empty($val) && empty($this->appt_medium)) {
            $this->attributes['appt_medium'] = $val;
        }
    }

    public function getAppointmentTypeAttribute(): ?string
    {
        return $this->service;
    }

    public function setAppointmentTypeAttribute($val): void
    {
        if (!empty($val) && empty($this->service)) {
            $this->attributes['service'] = $val;
        }
    }

    public function getAppointmentSubjectAttribute(): ?string
    {
        return $this->appt_code;
    }

    public function setAppointmentSubjectAttribute($val): void
    {
        if (!empty($val) && empty($this->appt_code)) {
            $this->attributes['appt_code'] = $val;
        }
    }

    public function getIsMaleAttribute(): bool
    {
        $g = (string) ($this->gender ?? '');
        return stripos($g, 'Male') !== false && stripos($g, 'Female') === false;
    }

    public function getIsFemaleAttribute(): bool
    {
        $g = (string) ($this->gender ?? '');
        return stripos($g, 'Female') !== false;
    }

    public function getGenderLabelAttribute(): string
    {
        if ($this->is_female) {
            return 'Female';
        }
        if ($this->is_male) {
            return 'Male';
        }
        return (string) ($this->gender ?? '');
    }

    /**
     * Check if a position title represents a School Principal or Acting Principal.
     */
    public static function isPrincipalPosition(?string $position): bool
    {
        if (!$position) {
            return false;
        }

        $pos = trim($position);

        if (str_starts_with($pos, '1 - Principal') || str_starts_with($pos, '2 - Acting Principal')) {
            return true;
        }

        if (stripos($pos, 'Principal') !== false
            && stripos($pos, 'Deputy') === false
            && stripos($pos, 'Assistant') === false
            && stripos($pos, 'Vice') === false) {
            return true;
        }

        return false;
    }

    /**
     * Assign principal details to the associated school and upgrade user role to School Admin.
     */
    public static function syncPrincipalToSchool(?School $school, ?string $position, string $name, string $nic, ?string $mobile = null, ?User $user = null): bool
    {
        if (!$school || !self::isPrincipalPosition($position)) {
            return false;
        }

        $school->update([
            'principalName' => $name,
            'principalNic' => $nic,
            'principalMobile' => $mobile ?: $school->principalMobile,
        ]);

        if ($user) {
            $user->update([
                'role' => User::ROLE_SCHOOL_ADMIN,
                'schoolId' => $school->id,
            ]);
        }

        return true;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::ulid();
            }
        });

        static::saved(function ($teacher) {
            $pos = $teacher->position ?: $teacher->currentPosition;
            if (self::isPrincipalPosition($pos)) {
                $school = null;
                if ($teacher->schoolId) {
                    $school = School::find($teacher->schoolId);
                } elseif ($teacher->census_no) {
                    $school = School::where('censusNo', $teacher->census_no)->first();
                }

                if ($school) {
                    $school->update([
                        'principalName' => $teacher->fullName ?: $teacher->nameWithInitials,
                        'principalNic' => $teacher->nic,
                        'principalMobile' => $teacher->phone ?: ($teacher->whatsapp ?: $school->principalMobile),
                    ]);

                    if ($teacher->userId) {
                        $user = User::find($teacher->userId);
                        if ($user) {
                            $user->update([
                                'role' => User::ROLE_SCHOOL_ADMIN,
                                'schoolId' => $school->id,
                            ]);
                        }
                    }
                }
            }
        });
    }
}
