<?php

namespace App\Http\Controllers\TeacherPanel;

use App\Http\Controllers\Controller;
use App\Models\SalaryRequest;
use Illuminate\Http\Request;

class SalaryRequestController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacherProfile;
        $requests = [];
        if ($teacher) {
            $requests = SalaryRequest::where('teacherId', $teacher->id)
                ->orderBy('createdAt', 'desc')
                ->get();
        }
        return view('dashboard.teacher.salary-requests', compact('teacher', 'requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documentType' => 'required|string',
            'periodType' => 'required|string',
            'periodDetails' => 'nullable|string',
        ]);

        $teacher = auth()->user()->teacherProfile;
        if (!$teacher) {
            return back()->withErrors(['error' => 'Teacher profile not found.']);
        }

        SalaryRequest::create([
            'teacherId' => $teacher->id,
            'schoolId' => $teacher->schoolId,
            'documentType' => $request->documentType,
            'periodType' => $request->periodType,
            'periodDetails' => $request->periodDetails,
            'status' => 'PENDING',
            'requestDate' => now(),
        ]);

        return back()->with('success', 'Salary request submitted!');
    }
}
