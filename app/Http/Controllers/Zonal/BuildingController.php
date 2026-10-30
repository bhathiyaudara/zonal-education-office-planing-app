<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Building;
use App\Models\School;
use App\Models\SchoolSanitation;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $request->get('school_id');
        $renovationOnly = $request->boolean('renovation_needed');
        $search = trim($request->get('search', ''));

        $query = Building::with('school')->orderBy('schoolId')->orderBy('buildingName');

        if ($schoolId) {
            $query->where('schoolId', $schoolId);
        }

        if ($renovationOnly) {
            $tenYearsAgo = (int)date('Y') - 10;
            $query->where(function ($q) use ($tenYearsAgo) {
                $q->whereNull('lastRepairYear')
                  ->orWhere('lastRepairYear', '<=', $tenYearsAgo);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('buildingName', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%")
                  ->orWhereHas('school', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('censusNo', 'like', "%{$search}%");
                  });
            });
        }

        $buildings = $query->get();

        // Statistics
        $totalBuildings = Building::count();
        $tenYearsAgo = (int)date('Y') - 10;
        $renovationCount = Building::where(function ($q) use ($tenYearsAgo) {
            $q->whereNull('lastRepairYear')
              ->orWhere('lastRepairYear', '<=', $tenYearsAgo);
        })->count();
        $multiFloorCount = Building::where('floors', '>', 1)->count();

        $totalBoysToilets = SchoolSanitation::sum('boysToilets');
        $totalGirlsToilets = SchoolSanitation::sum('girlsToilets');
        $totalTeacherToilets = SchoolSanitation::sum('teacherToilets');
        $totalSanitationUnits = $totalBoysToilets + $totalGirlsToilets + $totalTeacherToilets;

        $schools = School::orderBy('eduDivision')->orderBy('name')->get();
        $selectedSchool = $schoolId ? School::with('sanitation')->find($schoolId) : null;

        return view('dashboard.zonal.buildings.index', compact(
            'buildings',
            'schools',
            'schoolId',
            'selectedSchool',
            'totalBuildings',
            'renovationCount',
            'multiFloorCount',
            'totalSanitationUnits',
            'totalBoysToilets',
            'totalGirlsToilets',
            'totalTeacherToilets',
            'renovationOnly',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schoolId' => 'required|exists:school,id',
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
        if (empty($validated['status'])) {
            $validated['status'] = 'Good / Usable';
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

        $validated = $request->validate([
            'schoolId' => 'required|exists:school,id',
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
        $renovationOnly = $request->boolean('renovation_needed');
        $schoolId = $request->get('school_id');

        $query = Building::with('school')->orderBy('schoolId');

        if ($schoolId) {
            $query->where('schoolId', $schoolId);
        }

        if ($renovationOnly) {
            $tenYearsAgo = (int)date('Y') - 10;
            $query->where(function ($q) use ($tenYearsAgo) {
                $q->whereNull('lastRepairYear')
                  ->orWhere('lastRepairYear', '<=', $tenYearsAgo);
            });
        }

        $buildings = $query->get();

        $filename = 'wilgamuwa_buildings_' . ($renovationOnly ? 'needing_renovation_' : 'all_') . date('Y_m_d') . '.csv';

        return response()->streamDownload(function () use ($buildings, $renovationOnly) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                $renovationOnly 
                    ? 'Buildings Needing Renovation (Not repaired in last 10 years) - Wilgamuwa Zone'
                    : 'School Buildings Directory - Wilgamuwa Zone'
            ]);
            fputcsv($handle, ['Census No', 'School Name', 'Division', 'Building Name', 'Size', 'Floors', 'Construction Year', 'Last Repair Year', 'Age (Yrs)', 'Years Since Last Repair', 'Status', 'Notes']);

            foreach ($buildings as $b) {
                $age = $b->constructionYear ? (date('Y') - $b->constructionYear) : 'N/A';
                $yearsSince = $b->lastRepairYear ? (date('Y') - $b->lastRepairYear) : 'Never / Unknown';

                fputcsv($handle, [
                    $b->school->censusNo ?? 'N/A',
                    $b->school->name ?? 'Unknown',
                    $b->school->eduDivision ?? 'Wilgamuwa',
                    $b->buildingName,
                    $b->size ?? 'N/A',
                    $b->floors ?? 1,
                    $b->constructionYear ?? 'N/A',
                    $b->lastRepairYear ?? 'N/A',
                    $age,
                    $yearsSince,
                    $b->status ?? 'Good / Usable',
                    $b->notes ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
