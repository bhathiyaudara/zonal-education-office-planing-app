<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\StudentCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $school = School::with(['studentCounts' => function ($q) {
            $q->where('academicYear', date('Y'))->orderBy('grade');
        }])->findOrFail($user->schoolId);

        return view('dashboard.school.students.index', compact('school'));
    }

    public function updateCounts(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->schoolId;
        $year = date('Y');

        $data = $request->validate([
            'counts' => 'required|array',
            'counts.*.grade' => 'required|string',
            'counts.*.maleCount' => 'nullable|integer|min:0',
            'counts.*.femaleCount' => 'nullable|integer|min:0',
            'counts.*.classCount' => 'nullable|integer|min:0',
            'counts.*.classes' => 'nullable|string',
            'counts.*.classDetails' => 'nullable|array',
        ]);

        foreach ($data['counts'] as $countData) {
            StudentCount::updateOrCreate(
                [
                    'schoolId' => $schoolId,
                    'grade' => $countData['grade'],
                    'academicYear' => $year,
                ],
                [
                    'maleCount' => $countData['maleCount'] ?? 0,
                    'femaleCount' => $countData['femaleCount'] ?? 0,
                    'classCount' => $countData['classCount'] ?? 0,
                    'classes' => $countData['classes'] ?? null,
                    'classDetails' => $countData['classDetails'] ?? [],
                ]
            );
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Student counts updated successfully.']);
        }

        return back()->with('success', 'Student counts updated successfully.');
    }
}
