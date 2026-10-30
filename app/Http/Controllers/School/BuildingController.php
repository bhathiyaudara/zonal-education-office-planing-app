<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Building;
use App\Models\School;
use App\Models\SchoolSanitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $school = School::with(['buildings' => fn($q) => $q->orderBy('buildingName'), 'sanitation'])->findOrFail($user->schoolId);

        $buildings = $school->buildings;
        $sanitation = $school->sanitation;

        $tenYearsAgo = (int)date('Y') - 10;
        $renovationCount = $buildings->filter(function ($b) use ($tenYearsAgo) {
            return !$b->lastRepairYear || $b->lastRepairYear <= $tenYearsAgo;
        })->count();

        return view('dashboard.school.buildings.index', compact('school', 'buildings', 'sanitation', 'renovationCount'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $school = School::findOrFail($user->schoolId);

        $validated = $request->validate([
            'buildingName' => 'required|string|max:191',
            'size' => 'nullable|string|max:100',
            'floors' => 'nullable|integer|min:1|max:20',
            'constructionYear' => 'nullable|integer|min:1800|max:2050',
            'lastRepairYear' => 'nullable|integer|min:1800|max:2050',
            'status' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['schoolId'] = $school->id;
        if (empty($validated['floors'])) {
            $validated['floors'] = 1;
        }
        if (empty($validated['status'])) {
            $validated['status'] = 'Good / Usable';
        }

        $building = Building::create($validated);

        AuditLog::create([
            'userId' => $user->id,
            'userEmail' => $user->email,
            'action' => 'CREATE_BUILDING_SCHOOL',
            'resourceType' => 'building',
            'resourceId' => (string)$building->id,
            'details' => $validated,
        ]);

        return back()->with('success', "Building '{$building->buildingName}' added successfully!");
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $building = Building::where('schoolId', $user->schoolId)->findOrFail($id);

        $validated = $request->validate([
            'buildingName' => 'required|string|max:191',
            'size' => 'nullable|string|max:100',
            'floors' => 'nullable|integer|min:1|max:20',
            'constructionYear' => 'nullable|integer|min:1800|max:2050',
            'lastRepairYear' => 'nullable|integer|min:1800|max:2050',
            'status' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['floors'])) {
            $validated['floors'] = 1;
        }

        $building->update($validated);

        AuditLog::create([
            'userId' => $user->id,
            'userEmail' => $user->email,
            'action' => 'UPDATE_BUILDING_SCHOOL',
            'resourceType' => 'building',
            'resourceId' => (string)$building->id,
            'details' => $validated,
        ]);

        return back()->with('success', "Building '{$building->buildingName}' updated successfully!");
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $building = Building::where('schoolId', $user->schoolId)->findOrFail($id);
        $name = $building->buildingName;
        $building->delete();

        AuditLog::create([
            'userId' => $user->id,
            'userEmail' => $user->email,
            'action' => 'DELETE_BUILDING_SCHOOL',
            'resourceType' => 'building',
            'resourceId' => (string)$id,
            'details' => ['buildingName' => $name],
        ]);

        return back()->with('success', "Building '{$name}' deleted successfully!");
    }

    public function updateSanitation(Request $request)
    {
        $user = Auth::user();
        $school = School::findOrFail($user->schoolId);

        $validated = $request->validate([
            'boysToilets' => 'nullable|integer|min:0',
            'girlsToilets' => 'nullable|integer|min:0',
            'teacherToilets' => 'nullable|integer|min:0',
        ]);

        SchoolSanitation::updateOrInsert(
            ['schoolId' => $school->id],
            [
                'boysToilets' => (int)($validated['boysToilets'] ?? 0),
                'girlsToilets' => (int)($validated['girlsToilets'] ?? 0),
                'teacherToilets' => (int)($validated['teacherToilets'] ?? 0),
                'updatedAt' => now(),
            ]
        );

        AuditLog::create([
            'userId' => $user->id,
            'userEmail' => $user->email,
            'action' => 'UPDATE_SANITATION_SCHOOL',
            'resourceType' => 'school_sanitation',
            'resourceId' => (string)$school->id,
            'details' => $validated,
        ]);

        return back()->with('success', 'Toilet facilities updated successfully!');
    }
}
