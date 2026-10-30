<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Building;
use App\Models\School;
use App\Models\SchoolSanitation;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $school = School::with('sanitation')->findOrFail($user->schoolId);

        $buildingType = $request->get('building_type');
        $status = $request->get('status');
        $floors = $request->get('floors');
        $renovationOnly = $request->boolean('renovation_needed') || $request->boolean('needs_renovation');
        $search = trim($request->get('search', ''));

        $query = Building::where('schoolId', $school->id)->orderBy('buildingName');

        if ($buildingType) {
            $query->where('buildingType', $buildingType);
        }

        if ($status) {
            $prefix = explode(' (', $status)[0];
            $query->where(function ($q) use ($status, $prefix) {
                $q->where('status', $status)
                  ->orWhere('status', 'like', "%{$prefix}%");
            });
        }

        if ($floors !== null && $floors !== '') {
            if ($floors === 'multi') {
                $query->where('floors', '>', 1);
            } elseif ($floors === '3' || $floors === '3+') {
                $query->where('floors', '>=', 3);
            } else {
                $query->where('floors', (int)$floors);
            }
        }

        if ($renovationOnly) {
            $tenYearsAgo = (int)date('Y') - 10;
            $query->whereNotNull('lastRepairYear')
                  ->where('lastRepairYear', '<=', $tenYearsAgo);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('buildingName', 'like', "%{$search}%")
                  ->orWhere('buildingType', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $buildings = $query->get();
        $allSchoolBuildings = Building::where('schoolId', $school->id)->get();

        $tenYearsAgo = (int)date('Y') - 10;
        $renovationCount = $allSchoolBuildings->filter(function ($b) use ($tenYearsAgo) {
            return !empty($b->lastRepairYear) && $b->lastRepairYear <= $tenYearsAgo;
        })->count();

        $multiFloorCount = $allSchoolBuildings->where('floors', '>', 1)->count();
        $totalSqft = $allSchoolBuildings->sum('areaSqft');
        $sanitation = $school->sanitation;

        return view('dashboard.school.buildings.index', compact(
            'school', 'buildings', 'sanitation', 'renovationCount', 'multiFloorCount', 'totalSqft',
            'buildingType', 'status', 'floors', 'renovationOnly', 'search'
        ));
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

    /**
     * Export all buildings for the school with ALL collected database columns.
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $school = School::with('sanitation')->findOrFail($user->schoolId);

        $buildingType = $request->get('building_type');
        $status = $request->get('status');
        $floors = $request->get('floors');
        $renovationOnly = $request->boolean('renovation_needed') || $request->boolean('needs_renovation');
        $search = trim($request->get('search', ''));

        $query = Building::with(['school.sanitation'])
            ->where('schoolId', $school->id)
            ->orderBy('buildingName');

        if ($request->filled('ids')) {
            $ids = is_array($request->get('ids')) ? $request->get('ids') : explode(',', $request->get('ids'));
            $ids = array_filter(array_map('trim', $ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        } else {
            if ($buildingType) {
                $query->where('buildingType', $buildingType);
            }

            if ($status) {
                $prefix = explode(' (', $status)[0];
                $query->where(function ($q) use ($status, $prefix) {
                    $q->where('status', $status)
                      ->orWhere('status', 'like', "%{$prefix}%");
                });
            }

            if ($floors !== null && $floors !== '') {
                if ($floors === 'multi') {
                    $query->where('floors', '>', 1);
                } elseif ($floors === '3' || $floors === '3+') {
                    $query->where('floors', '>=', 3);
                } else {
                    $query->where('floors', (int)$floors);
                }
            }

            if ($renovationOnly) {
                $tenYearsAgo = (int)date('Y') - 10;
                $query->whereNotNull('lastRepairYear')
                      ->where('lastRepairYear', '<=', $tenYearsAgo);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('buildingName', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%")
                      ->orWhere('size', 'like', "%{$search}%");
                });
            }
        }

        $buildings = $query->get();
        $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', $school->name ?: 'School');
        $filename = 'Buildings_' . $safeName . '_' . date('Y_m_d_His') . '.csv';

        return ExportService::exportBuildings($buildings, $filename);
    }
}
