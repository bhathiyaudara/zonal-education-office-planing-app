<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Authenticate as Zonal Admin
$adminUser = \App\Models\User::where('role', \App\Models\User::ROLE_ZONAL_ADMIN ?? 'ZONAL_ADMIN')->first();
if (!$adminUser) {
    $adminUser = new \App\Models\User([
        'id' => 'preview-admin',
        'name' => 'Zonal Admin Preview',
        'email' => 'admin@zeowilgamuwa.lk',
        'role' => 'ZONAL_ADMIN',
        'isActive' => true,
    ]);
}
\Illuminate\Support\Facades\Auth::login($adminUser);

$schools = \App\Models\School::all();
$school = $schools->first();
$buildings = \App\Models\Building::with('school')->take(20)->get();

$totalBuildings = \App\Models\Building::count();
$learningCount = \App\Models\Building::where('buildingType', 'like', '%Learning%')->orWhere('buildingType', 'like', '%ඉගෙනුම්%')->count();
$adminCount = \App\Models\Building::where('buildingType', 'like', '%Administrative%')->orWhere('buildingType', 'like', '%පරිපාලන%')->count();
$quartersCount = \App\Models\Building::where('buildingType', 'like', '%Quarters%')->orWhere('buildingType', 'like', '%නිවාස%')->count();

$tenYearsAgo = (int)date('Y') - 10;
$renovationCount = \App\Models\Building::whereNotNull('lastRepairYear')
    ->where('lastRepairYear', '<=', $tenYearsAgo)
    ->count();
$multiFloorCount = \App\Models\Building::where('floors', '>', 1)->count();

$totalBoysToilets = \App\Models\SchoolSanitation::sum('boysToilets');
$totalGirlsToilets = \App\Models\SchoolSanitation::sum('girlsToilets');
$totalTeacherToilets = \App\Models\SchoolSanitation::sum('teacherToilets');
$totalSanitationUnits = $totalBoysToilets + $totalGirlsToilets + $totalTeacherToilets;

$totalReqBoysToilets = \App\Models\SchoolSanitation::sum('reqBoysToilets');
$totalReqGirlsToilets = \App\Models\SchoolSanitation::sum('reqGirlsToilets');
$totalReqTeacherToilets = \App\Models\SchoolSanitation::sum('reqTeacherToilets');
$totalReqSanitationUnits = $totalReqBoysToilets + $totalReqGirlsToilets + $totalReqTeacherToilets;

echo view('dashboard.zonal.buildings.index', [
    'buildings' => $buildings,
    'schools' => $schools,
    'schoolId' => $school ? $school->id : null,
    'buildingType' => null,
    'selectedSchool' => $school,
    'renovationOnly' => false,
    'search' => '',
    'totalBuildings' => $totalBuildings,
    'learningCount' => $learningCount,
    'adminCount' => $adminCount,
    'quartersCount' => $quartersCount,
    'renovationCount' => $renovationCount,
    'multiFloorCount' => $multiFloorCount,
    'totalSanitationUnits' => $totalSanitationUnits,
    'totalBoysToilets' => $totalBoysToilets,
    'totalGirlsToilets' => $totalGirlsToilets,
    'totalTeacherToilets' => $totalTeacherToilets,
    'totalReqSanitationUnits' => $totalReqSanitationUnits,
    'totalReqBoysToilets' => $totalReqBoysToilets,
    'totalReqGirlsToilets' => $totalReqGirlsToilets,
    'totalReqTeacherToilets' => $totalReqTeacherToilets,
])->render();
