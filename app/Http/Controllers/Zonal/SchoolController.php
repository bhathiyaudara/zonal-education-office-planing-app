<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\StudentCount;
use App\Services\ExportService;
use App\Services\StudentCensusService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $schools = School::withCount('teachers')
            ->orderBy('name')
            ->get();

        $totalSchools = $schools->count();

        $teachers = \App\Models\Teacher::select('id', 'nic', 'fullName', 'phone')->get();

        return view('dashboard.zonal.schools.index', compact('schools', 'totalSchools', 'teachers'));
    }

    public function show(string $id)
    {
        $school = School::with([
            'teachers' => fn($q) => $q->orderBy('fullName'),
            'studentCounts' => fn($q) => $q->whereIn('academicYear', [request('year', '2025'), date('Y'), '2025', '2026'])->orderBy('grade'),
            'buildings' => fn($q) => $q->orderBy('buildingName'),
            'sanitation',
        ])->findOrFail($id);

        return view('dashboard.zonal.schools.show', compact('school'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'censusNo' => 'nullable|string|max:191|unique:school,censusNo',
            'address' => 'nullable|string|max:191',
            'landArea' => 'nullable|string|max:191',
            'difficultyCategory' => 'nullable|string|max:100',
            'contactNumber' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'schoolType' => 'nullable|string|max:191',
            'eduZone' => 'nullable|string|max:191',
            'eduDivision' => 'nullable|string|max:191',
            'dsDivision' => 'nullable|string|max:191',
            'gnDivision' => 'nullable|string|max:191',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'principalName' => 'nullable|string|max:191',
            'principalNic' => 'nullable|string|max:191',
            'principalMobile' => 'nullable|string|max:191',
            'gradeStart' => 'nullable|integer|min:1|max:13',
            'gradeEnd' => 'nullable|integer|min:1|max:13',
        ]);

        if (!isset($validated['eduZone'])) {
            $validated['eduZone'] = 'Wilgamuwa';
        }

        $school = School::create($validated);

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'CREATE_SCHOOL',
            'resourceType' => 'school',
            'resourceId' => $school->id,
            'details' => $validated,
        ]);

        return back()->with('success', 'School created successfully!');
    }

    public function update(Request $request, string $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'censusNo' => 'nullable|string|max:191|unique:school,censusNo,' . $id,
            'address' => 'nullable|string|max:191',
            'landArea' => 'nullable|string|max:191',
            'difficultyCategory' => 'nullable|string|max:100',
            'contactNumber' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'schoolType' => 'nullable|string|max:191',
            'eduZone' => 'nullable|string|max:191',
            'eduDivision' => 'nullable|string|max:191',
            'dsDivision' => 'nullable|string|max:191',
            'gnDivision' => 'nullable|string|max:191',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'principalName' => 'nullable|string|max:191',
            'principalNic' => 'nullable|string|max:191',
            'principalMobile' => 'nullable|string|max:191',
            'gradeStart' => 'nullable|integer|min:1|max:13',
            'gradeEnd' => 'nullable|integer|min:1|max:13',
            'sanctionedPositions' => 'nullable|integer|min:0',
        ]);

        $school->update($validated);

        return back()->with('success', 'School updated successfully!');
    }

    public function destroy(string $id)
    {
        $school = School::findOrFail($id);
        $name = $school->name;
        $school->delete();

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'DELETE_SCHOOL',
            'resourceType' => 'school',
            'resourceId' => $id,
            'details' => ['name' => $name],
        ]);

        return back()->with('success', "School '{$name}' removed.");
    }

    public function updateStudentCounts(Request $request, string $id)
    {
        $year = date('Y');
        $data = $request->validate([
            'counts' => 'required|array',
            'counts.*.grade' => 'required|string',
            'counts.*.maleCount' => 'required|integer|min:0',
            'counts.*.femaleCount' => 'required|integer|min:0',
            'counts.*.classCount' => 'nullable|integer|min:0',
            'counts.*.classes' => 'nullable|string',
            'counts.*.classDetails' => 'nullable|array',
        ]);

        foreach ($data['counts'] as $countData) {
            \App\Models\StudentCount::updateOrCreate(
                [
                    'schoolId' => $id,
                    'grade' => $countData['grade'],
                    'academicYear' => $year,
                ],
                [
                    'maleCount' => $countData['maleCount'],
                    'femaleCount' => $countData['femaleCount'],
                    'classCount' => $countData['classCount'] ?? 0,
                    'classes' => $countData['classes'] ?? null,
                    'classDetails' => $countData['classDetails'] ?? [],
                ]
            );
        }

        return back()->with('success', 'Student counts updated successfully.');
    }

    public function studentOverview(Request $request)
    {
        $academicYear = $request->get('academic_year', '2025');

        // Retrieve Zonal High-level KPIs & Subject data from StudentCensusService
        $kpis = StudentCensusService::getZonalKPIs();
        $schoolsCensus = StudentCensusService::getSchoolsCensus();
        $olSubjects = StudentCensusService::getOlSubjects();
        $alSubjects = StudentCensusService::getAlSubjects();
        $alStreams = StudentCensusService::getAlStreams();
        $pirivenas = StudentCensusService::getPirivenaCensus();

        $censusByNo = collect($schoolsCensus)->keyBy('census_no');

        // Query active schools from database
        $schools = School::with([
                'studentCounts' => fn($q) => $q->whereIn('academicYear', [$academicYear, '2025', '2026']),
            ])
            ->where('isActive', true)
            ->orderBy('name')
            ->get();

        // Compute per-school aggregates
        $schoolsData = $schools->map(function ($school) use ($censusByNo, $academicYear) {
            $cData = $censusByNo->get($school->censusNo);
            $sc = $school->studentCounts->where('academicYear', $academicYear);
            if ($sc->isEmpty()) {
                $sc = $school->studentCounts->where('academicYear', '2025');
            }

            $totalMale = $sc->sum('maleCount');
            $totalFemale = $sc->sum('femaleCount');
            $totalClasses = $sc->sum('classCount');

            if ($totalMale + $totalFemale == 0 && $cData) {
                $totalMale = $cData['grand_total']['male'] ?? 0;
                $totalFemale = $cData['grand_total']['female'] ?? 0;
                $totalClasses = $cData['total_classes'] ?? 0;
            }

            return [
                'id' => $school->id,
                'name' => $school->name,
                'name_si' => $school->nameSinhala ?: ($cData['name_si'] ?? ''),
                'censusNo' => $school->censusNo ?? '',
                'schoolType' => $school->schoolType ?? 'N/A',
                'eduDivision' => $school->eduDivision ?? ($cData['division'] ?? 'N/A'),
                'difficultyCategory' => $school->difficultyCategory ?? null,
                'dsDivision' => $school->dsDivision ?? null,
                'gnDivision' => $school->gnDivision ?? null,
                'landArea' => $school->landArea ?? null,
                'category' => $cData['grand_total']['category'] ?? '',
                'notes' => $cData['grand_total']['notes'] ?? '',
                'totalStudents' => $totalMale + $totalFemale,
                'totalMale' => $totalMale,
                'totalFemale' => $totalFemale,
                'totalClasses' => $totalClasses,
                'primary_total' => $cData['primary_total']['total'] ?? 0,
                'primary_classes' => $cData['primary_total']['classes'] ?? 0,
                'secondary_total' => $cData['secondary_total']['total'] ?? 0,
                'secondary_classes' => $cData['secondary_total']['classes'] ?? 0,
                'collegiate_total' => $cData['collegiate_total']['total'] ?? 0,
                'collegiate_classes' => $cData['collegiate_total']['classes'] ?? 0,
                'special_ed' => $cData['special_ed']['total'] ?? 0,
                'grades' => $cData['grades'] ?? [],
            ];
        });

        // Zone-level aggregates (Fallback to KPIs if DB counts not yet fully hydrated)
        $totalStudents = $schoolsData->sum('totalStudents') ?: $kpis['totalStudents'];
        $totalMale = $schoolsData->sum('totalMale') ?: $kpis['totalMale'];
        $totalFemale = $schoolsData->sum('totalFemale') ?: $kpis['totalFemale'];
        $totalSchools = $schoolsData->count() ?: $kpis['totalSchools'];

        $wilgamuwaStudents = $schoolsData->where('eduDivision', 'Wilgamuwa')->sum('totalStudents') ?: $kpis['wilgamuwaStudents'];
        $laggalaStudents = $schoolsData->where('eduDivision', 'Laggala')->sum('totalStudents') ?: $kpis['laggalaStudents'];

        return view('dashboard.zonal.schools.student-overview', compact(
            'schoolsData', 'academicYear', 'totalStudents', 'totalMale',
            'totalFemale', 'totalSchools', 'wilgamuwaStudents', 'laggalaStudents',
            'kpis', 'olSubjects', 'alSubjects', 'alStreams', 'pirivenas'
        ));
    }

    public function removeForm()
    {
        $schools = School::where('isActive', true)
            ->orderBy('name')
            ->get(['id', 'name', 'censusNo', 'eduDivision']);

        return view('dashboard.zonal.schools.remove', compact('schools'));
    }

    /**
     * Export schools directory with ALL database columns and infrastructure summaries.
     */
    public function export(Request $request)
    {
        $query = School::with(['sanitation', 'buildings', 'studentCounts'])
            ->withCount('teachers')
            ->where('isActive', true)
            ->orderBy('name');

        if ($request->filled('ids')) {
            $ids = is_array($request->get('ids')) ? $request->get('ids') : explode(',', $request->get('ids'));
            $ids = array_filter(array_map('trim', $ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        } else {
            if ($search = trim($request->get('search', ''))) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('censusNo', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            }

            if ($schoolType = $request->get('school_type')) {
                $query->where('schoolType', $schoolType);
            }

            if ($division = $request->get('division') ?: $request->get('edu_division')) {
                $query->where('eduDivision', $division);
            }

            if ($dsDivision = $request->get('ds_division')) {
                $query->where('dsDivision', $dsDivision);
            }

            if ($difficulty = $request->get('difficulty') ?: $request->get('difficulty_category')) {
                $query->where('difficultyCategory', 'like', "%{$difficulty}%");
            }
        }

        $schools = $query->get();
        $filename = 'Wilgamuwa_Schools_Master_Export_' . date('Y_m_d_His') . '.csv';

        return ExportService::exportSchools($schools, $filename);
    }

    /**
     * Export student overview with ALL database columns + Grade 1-13 breakdown.
     */
    public function exportStudentOverview(Request $request)
    {
        $exportType = $request->get('type', 'grades');

        if ($exportType === 'ol_subjects') {
            $olData = StudentCensusService::getOlSubjects();
            return ExportService::exportOlSubjectsMatrix($olData);
        }

        if ($exportType === 'al_subjects') {
            $alData = StudentCensusService::getAlSubjects();
            return ExportService::exportAlSubjectsMatrix($alData);
        }

        if ($exportType === 'al_streams') {
            $streamData = StudentCensusService::getAlStreams();
            return ExportService::exportAlStreamsMatrix($streamData);
        }

        $currentYear = date('Y');
        $fallbackYear = $currentYear . '/' . ($currentYear + 1);
        $academicYear = $request->get('academic_year') ?: (
            StudentCount::where('academicYear', '2025')->exists() ? '2025' : $fallbackYear
        );

        $query = School::with([
                'studentCounts' => fn($q) => $q->whereIn('academicYear', [$academicYear, $fallbackYear, '2025', '2026']),
                'sanitation',
                'buildings'
            ])
            ->where('isActive', true)
            ->orderBy('name');

        if ($request->filled('ids')) {
            $ids = is_array($request->get('ids')) ? $request->get('ids') : explode(',', $request->get('ids'));
            $ids = array_filter(array_map('trim', $ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        } else {
            if ($search = trim($request->get('search', ''))) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('censusNo', 'like', "%{$search}%");
                });
            }

            if ($division = $request->get('division')) {
                if (strcasecmp($division, 'All') !== 0) {
                    $query->where('eduDivision', $division);
                }
            }

            if ($schoolType = $request->get('school_type')) {
                $query->where('schoolType', $schoolType);
            }
        }

        $schools = $query->get();
        $filename = 'Wilgamuwa_Students_Enrollment_All_Grades_' . str_replace('/', '-', $academicYear) . '_' . date('Y_m_d_His') . '.csv';

        return ExportService::exportStudentOverview($schools, $academicYear, $filename);
    }
}
