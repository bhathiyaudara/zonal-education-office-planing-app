<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $school = School::findOrFail($user->schoolId);

        return view('dashboard.school.edit', compact('school'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $school = School::findOrFail($user->schoolId);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'censusNo' => 'nullable|string|max:191|unique:school,censusNo,' . $school->id,
            'address' => 'nullable|string|max:191',
            'contactNumber' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'schoolType' => 'nullable|string|max:191',
            'eduDivision' => 'nullable|string|max:191',
            'principalName' => 'nullable|string|max:191',
            'principalNic' => 'nullable|string|max:191',
            'principalMobile' => 'nullable|string|max:191',
            'gradeStart' => 'nullable|integer|min:1|max:13',
            'gradeEnd' => 'nullable|integer|min:1|max:13',
            'sanctionedPositions' => 'nullable|integer|min:0',
        ]);

        $school->update($validated);

        return redirect()->route('dashboard')->with('success', 'School profile updated successfully!');
    }
}
