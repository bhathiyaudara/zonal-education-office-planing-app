<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Teacher;
use App\Models\School;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // Retroactively assign existing teachers with Principal position to their respective schools
        $teachers = Teacher::where(function ($q) {
            $q->where('position', 'like', '%Principal%')
              ->orWhere('currentPosition', 'like', '%Principal%');
        })->get();

        foreach ($teachers as $teacher) {
            $pos = $teacher->position ?: $teacher->currentPosition;
            if (Teacher::isPrincipalPosition($pos)) {
                $school = null;
                if ($teacher->schoolId) {
                    $school = School::find($teacher->schoolId);
                } elseif ($teacher->census_no) {
                    $school = School::where('censusNo', $teacher->census_no)->first();
                }

                if ($school) {
                    $school->update([
                        'principalName' => $teacher->nameWithInitials ?: $teacher->fullName,
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
        }
    }

    public function down(): void
    {
        // No-op
    }
};
