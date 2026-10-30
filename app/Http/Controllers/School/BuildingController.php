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
            return !empty($b->lastRepairYear) && $b->lastRepairYear <= $tenYearsAgo;
        })->count();

        $multiFloorCount = $buildings->where('floors', '>', 1)->count();
        $totalSqft = $buildings->sum('areaSqft');

        return view('dashboard.school.buildings.index', compact('school', 'buildings', 'sanitation', 'renovationCount', 'multiFloorCount', 'totalSqft'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $school = School::findOrFail($user->schoolId);

        $validated = $request->validateWithBag('building', [
            'buildingName' => 'required|string|max:191',
            'buildingType' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'lengthFeet' => 'nullable|numeric|min:0|max:99999.99',
            'widthFeet' => 'nullable|numeric|min:0|max:99999.99',
            'areaSqft' => 'nullable|numeric|min:0|max:9999999.99',
            'floors' => 'nullable|integer|min:1|max:20',
            'constructionYear' => 'nullable|integer|min:1800|max:2050',
            'lastRepairYear' => 'nullable|integer|min:1800|max:2050',
            'status' => 'nullable|string|max:191',
            'notes' => 'nullable|string',
        ]);

        $validated['schoolId'] = $school->id;
        if (empty($validated['floors'])) {
            $validated['floors'] = 1;
        }
        if (empty($validated['buildingType'])) {
            $validated['buildingType'] = 'Learning / ඉගෙනුම්';
        }
        if (empty($validated['status'])) {
            $validated['status'] = 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)';
        }

        // Auto-calculate Area & Size if length and width are specified
        if (!empty($validated['lengthFeet']) && !empty($validated['widthFeet'])) {
            if (empty($validated['areaSqft'])) {
                $validated['areaSqft'] = round((float)$validated['lengthFeet'] * (float)$validated['widthFeet'] * max(1, (int)$validated['floors']), 2);
            }
            if (empty($validated['size'])) {
                $validated['size'] = "{$validated['lengthFeet']}' x {$validated['widthFeet']}'" . ($validated['areaSqft'] ? " ({$validated['areaSqft']} sqft)" : "");
            }
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

        $validated = $request->validateWithBag('building', [
            'buildingName' => 'required|string|max:191',
            'buildingType' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'lengthFeet' => 'nullable|numeric|min:0|max:99999.99',
            'widthFeet' => 'nullable|numeric|min:0|max:99999.99',
            'areaSqft' => 'nullable|numeric|min:0|max:9999999.99',
            'floors' => 'nullable|integer|min:1|max:20',
            'constructionYear' => 'nullable|integer|min:1800|max:2050',
            'lastRepairYear' => 'nullable|integer|min:1800|max:2050',
            'status' => 'nullable|string|max:191',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['floors'])) {
            $validated['floors'] = 1;
        }
        if (empty($validated['buildingType'])) {
            $validated['buildingType'] = 'Learning / ඉගෙනුම්';
        }

        // Auto-calculate Area & Size if length and width are specified
        if (!empty($validated['lengthFeet']) && !empty($validated['widthFeet'])) {
            if (empty($validated['areaSqft'])) {
                $validated['areaSqft'] = round((float)$validated['lengthFeet'] * (float)$validated['widthFeet'] * max(1, (int)$validated['floors']), 2);
            }
            if (empty($validated['size'])) {
                $validated['size'] = "{$validated['lengthFeet']}' x {$validated['widthFeet']}'" . ($validated['areaSqft'] ? " ({$validated['areaSqft']} sqft)" : "");
            }
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
            'reqBoysToilets' => 'nullable|integer|min:0',
            'reqGirlsToilets' => 'nullable|integer|min:0',
            'reqTeacherToilets' => 'nullable|integer|min:0',
        ]);

        SchoolSanitation::updateOrInsert(
            ['schoolId' => $school->id],
            [
                'reqBoysToilets' => (int)($validated['reqBoysToilets'] ?? 0),
                'reqGirlsToilets' => (int)($validated['reqGirlsToilets'] ?? 0),
                'reqTeacherToilets' => (int)($validated['reqTeacherToilets'] ?? 0),
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
