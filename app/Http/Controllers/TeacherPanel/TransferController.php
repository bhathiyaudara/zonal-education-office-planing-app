<?php

namespace App\Http\Controllers\TeacherPanel;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $teacher = $user->teacherProfile;
        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name']);

        $transfers = [];
        if ($teacher) {
            $transfers = TransferRequest::with(['fromSchool', 'toSchool'])
                ->where('teacherId', $teacher->id)
                ->orderBy('createdAt', 'desc')
                ->get();
        }

        return view('dashboard.teacher.transfer', compact('teacher', 'schools', 'transfers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'toSchoolId' => 'required|string|exists:school,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $teacher = auth()->user()->teacherProfile;
        if (!$teacher) {
            return back()->withErrors(['error' => 'Teacher profile not found.']);
        }

        TransferRequest::create([
            'teacherId' => $teacher->id,
            'fromSchoolId' => $teacher->schoolId,
            'toSchoolId' => $request->toSchoolId,
            'reason' => $request->reason,
            'status' => 'PENDING',
        ]);

        return back()->with('success', 'Transfer request submitted!');
    }
}
