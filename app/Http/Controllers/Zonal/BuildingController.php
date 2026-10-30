<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Building;
use App\Models\School;
use App\Models\SchoolSanitation;
use App\Services\ExportService;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $request->get('school_id');
        $buildingType = $request->get('building_type');
        $status = $request->get('status');
        $floors = $request->get('floors');
        $renovationOnly = $request->boolean('renovation_needed') || $request->boolean('needs_renovation');
        $search = trim($request->get('search', ''));

        $query = Building::with('school')->orderBy('schoolId')->orderBy('buildingName');

        if ($schoolId) {
            $query->where('schoolId', $schoolId);
        }

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
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('school', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('censusNo', 'like', "%{$search}%");
                  });
            });
        }

        $buildings = $query->get();

        // Statistics
        $totalBuildings = Building::count();
        $learningCount = Building::where(function ($q) {
            $q->where('buildingType', 'like', '%Learning%')
              ->orWhere('buildingType', 'like', '%ඉගෙනුම්%');
        })->count();
        $adminCount = Building::where(function ($q) {
            $q->where('buildingType', 'like', '%Administrative%')
              ->orWhere('buildingType', 'like', '%පරිපාලන%');
        })->count();
        $quartersCount = Building::where(function ($q) {
            $q->where('buildingType', 'like', '%Quarters%')
              ->orWhere('buildingType', 'like', '%නිවාස%');
        })->count();

        $tenYearsAgo = (int)date('Y') - 10;
        $renovationCount = Building::whereNotNull('lastRepairYear')
            ->where('lastRepairYear', '<=', $tenYearsAgo)
            ->count();
        $multiFloorCount = Building::where('floors', '>', 1)->count();

        $totalBoysToilets = SchoolSanitation::sum('boysToilets');
        $totalGirlsToilets = SchoolSanitation::sum('girlsToilets');
        $totalTeacherToilets = SchoolSanitation::sum('teacherToilets');
        $totalSanitationUnits = $totalBoysToilets + $totalGirlsToilets + $totalTeacherToilets;

        $totalReqBoysToilets = SchoolSanitation::sum('reqBoysToilets');
        $totalReqGirlsToilets = SchoolSanitation::sum('reqGirlsToilets');
        $totalReqTeacherToilets = SchoolSanitation::sum('reqTeacherToilets');
        $totalReqSanitationUnits = $totalReqBoysToilets + $totalReqGirlsToilets + $totalReqTeacherToilets;

        $schools = School::orderBy('eduDivision')->orderBy('name')->get();
        $selectedSchool = $schoolId ? School::with('sanitation')->find($schoolId) : null;

        return view('dashboard.zonal.buildings.index', compact(
            'buildings',
            'schools',
            'schoolId',
            'buildingType',
            'selectedSchool',
            'totalBuildings',
            'learningCount',
            'adminCount',
            'quartersCount',
            'renovationCount',
            'multiFloorCount',
            'totalSanitationUnits',
            'totalBoysToilets',
            'totalGirlsToilets',
            'totalTeacherToilets',
            'totalReqSanitationUnits',
            'totalReqBoysToilets',
            'totalReqGirlsToilets',
            'totalReqTeacherToilets',
            'renovationOnly',
            'status',
            'floors',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('building', [
            'schoolId' => 'required|exists:school,id',
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
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'CREATE_BUILDING',
            'resourceType' => 'building',
            'resourceId' => (string)$building->id,
            'details' => $validated,
        ]);

        return back()->with('success', "Building '{$building->buildingName}' added successfully!");
    }

    public function update(Request $request, $id)
    {
        $building = Building::findOrFail($id);

        $validated = $request->validateWithBag('building', [
            'schoolId' => 'required|exists:school,id',
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
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'UPDATE_BUILDING',
            'resourceType' => 'building',
            'resourceId' => (string)$building->id,
            'details' => $validated,
        ]);

        return back()->with('success', "Building '{$building->buildingName}' updated successfully!");
    }

    public function destroy($id)
    {
        $building = Building::findOrFail($id);
        $name = $building->buildingName;
        $building->delete();

        AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'DELETE_BUILDING',
            'resourceType' => 'building',
            'resourceId' => (string)$id,
            'details' => ['buildingName' => $name],
        ]);

        return back()->with('success', "Building '{$name}' deleted successfully!");
    }

    public function updateSanitation(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);

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
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'UPDATE_SANITATION',
            'resourceType' => 'school_sanitation',
            'resourceId' => (string)$school->id,
            'details' => $validated,
        ]);

        return back()->with('success', "Toilet facilities updated for {$school->name}!");
    }

    public function export(Request $request)
    {
        $renovationOnly = $request->boolean('renovation_needed') || $request->boolean('needs_renovation');
        $schoolId = $request->get('school_id');
        $buildingType = $request->get('building_type');
        $status = $request->get('status');
        $floors = $request->get('floors');
        $search = trim($request->get('search', ''));

        $query = Building::with(['school.sanitation'])->orderBy('schoolId');

        if ($request->filled('ids')) {
            $ids = is_array($request->get('ids')) ? $request->get('ids') : explode(',', $request->get('ids'));
            $ids = array_filter(array_map('trim', $ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        } else {
            if ($schoolId) {
                $query->where('schoolId', $schoolId);
            }

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
                      ->orWhere('size', 'like', "%{$search}%")
                      ->orWhereHas('school', function ($sq) use ($search) {
                          $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('censusNo', 'like', "%{$search}%");
                      });
                });
            }
        }

        $buildings = $query->get();
        $filename = 'Wilgamuwa_Buildings_' . ($renovationOnly ? 'Needing_Renovation_' : 'All_') . date('Y_m_d_His') . '.csv';

        return ExportService::exportBuildings($buildings, $filename);
    }
}
