<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\StudentCount;
use App\Models\Teacher;
use App\Models\TransferRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            User::ROLE_ZONAL_ADMIN => $this->zonalDashboard(),
            User::ROLE_SCHOOL_ADMIN => $this->schoolDashboard(),
            User::ROLE_TEACHER => $this->teacherDashboard(),
            default => abort(403),
        };
    }

    private function zonalDashboard()
    {
        $teacherCount = Teacher::count();
        $schoolCount = School::count();
        $pendingTransfers = TransferRequest::where('status', 'PENDING')->count();

        $year = date('Y');
        $studentAgg = StudentCount::where('academicYear', $year)
            ->selectRaw('COALESCE(SUM(maleCount),0) as totalMale, COALESCE(SUM(femaleCount),0) as totalFemale')
            ->first();
        $totalStudents = ($studentAgg->totalMale ?? 0) + ($studentAgg->totalFemale ?? 0);

        return view('dashboard.zonal.index', compact(
            'teacherCount', 'schoolCount', 'pendingTransfers', 'totalStudents'
        ));
    }

    private function schoolDashboard()
    {
        $user = Auth::user();
        $school = School::with(['teachers.subjects.subject', 'studentCounts' => function ($q) {
            $q->where('academicYear', date('Y'))->orderByRaw("CAST(SUBSTRING(grade, 7) AS UNSIGNED) ASC");
        }])->find($user->schoolId);

        if (!$school) {
            abort(404, 'No school assigned.');
        }

        return view('dashboard.school.index', compact('school'));
    }

    private function teacherDashboard()
    {
        $user = Auth::user();
        $teacher = Teacher::with(['school', 'spouseSchool', 'subjects.subject'])
            ->where('userId', $user->id)
            ->first();

        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name']);

        return view('dashboard.teacher.index', compact('teacher', 'schools'));
    }
}
