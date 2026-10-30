<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$schools = \App\Models\School::all();
$school = $schools->first() ?? new \App\Models\School(['id' => 's1', 'name' => 'Demo School']);

echo view('dashboard.zonal.buildings.index', [
    'buildings' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
    'schools' => $schools->isNotEmpty() ? $schools : collect([$school]),
    'selectedSchool' => $school, 'schoolId' => $school->id,
    'renovationOnly' => false, 'search' => '',
    'totalBuildings' => 0, 'renovationCount' => 0, 'multiFloorCount' => 0,
    'totalSanitationUnits' => 0, 'totalBoysToilets' => 0, 'totalGirlsToilets' => 0, 'totalTeacherToilets' => 0,
])->render();