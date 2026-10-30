<?php

namespace App\Services;

use App\Models\Building;
use App\Models\School;
use App\Models\Teacher;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Stream a CSV file with UTF-8 BOM for Microsoft Excel / Sheets compatibility.
     */
    public static function streamCsv(string $filename, array $headers, iterable $records, callable $rowTransformer): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $records, $rowTransformer) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel properly displays Sinhala and special characters
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write Header Row
            fputcsv($handle, $headers);

            // Write Data Rows
            foreach ($records as $record) {
                $row = $rowTransformer($record);
                if ($row !== null) {
                    fputcsv($handle, $row);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Export teachers dataset with ALL collected database columns.
     */
    public static function exportTeachers(iterable $teachers, ?string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Wilgamuwa_Teachers_Full_Database_Export_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Teacher Record ID',
            'School Census No',
            'School Name',
            'Education Division',
            'DS Division (DSD)',
            'GN Division (GND)',
            'School Difficulty Category',
            'Teacher Census No',
            'Salutation / Title',
            'Full Name',
            'NIC Number',
            'Paysheet Number',
            'Personal File Number',
            'Date of Birth',
            'Age (Years)',
            'Gender',
            'Ethnicity',
            'Ethnicity (Other)',
            'Religion',
            'Religion (Other)',
            'Marital Status',
            'Marital Status (Other)',
            'Permanent Address',
            'Contact Phone',
            'Mobile Phone',
            'WhatsApp Number',
            'Email Address',
            'Highest Educational Qualification',
            'A/L Subject Stream',
            'Degree / Field of Study',
            'Field of Study (Other)',
            'Professional Qualification',
            'Professional Qualification (Other)',
            'All Professional Qualifications',
            'Service Sector',
            'First Appointment Date',
            'First Appointment Class / Grade',
            'Current Grade Appointment Date',
            'School Appointment Date',
            'Years at Current School',
            'Retirement Date (Age 60)',
            'Years Left to Retirement',
            'Appointment Section',
            'Appointment Category',
            'Appointment Subject Code',
            'Appointment Medium',
            'Main Duty',
            'Main Duty (Other)',
            'Second Duty',
            'Second Duty (Other)',
            'Teaching Subject 1 Section',
            'Teaching Subject 1 Category',
            'Teaching Subject 1 Code & Subject',
            'Teaching Subject 1 Medium',
            'Teaching Subject 1 Periods',
            'Teaching Subject 2 Section',
            'Teaching Subject 2 Category',
            'Teaching Subject 2 Code & Subject',
            'Teaching Subject 2 Medium',
            'Teaching Subject 2 Periods',
            'Capable Subject 1 Section',
            'Capable Subject 1 Category',
            'Capable Subject 1 Code & Subject',
            'Capable Subject 1 Medium',
            'Capable Subject 2 Section',
            'Capable Subject 2 Category',
            'Capable Subject 2 Code & Subject',
            'Capable Subject 2 Medium',
            'Attached Status',
            'Attached Institution Census / Code',
            'Attached Institution Name',
            'Attached Days Per Week',
            'Current Position / Designation',
            'Salary Paying Authority',
            'Service Based',
            'Salary Source / Budget Head',
            'Record Created At',
            'Record Last Updated At'
        ];

        $formatArray = function ($val) {
            if (empty($val)) return '';
            if (is_array($val)) {
                $flat = [];
                array_walk_recursive($val, function ($v) use (&$flat) {
                    if (is_scalar($v) && trim((string)$v) !== '') {
                        $flat[] = trim((string)$v);
                    }
                });
                return implode('; ', array_unique($flat));
            }
            if (is_string($val)) {
                $decoded = json_decode($val, true);
                if (is_array($decoded)) {
                    return implode('; ', array_filter($decoded, 'is_scalar'));
                }
            }
            return (string)$val;
        };

        $formatDate = function ($date) {
            if (!$date) return '';
            try {
                if ($date instanceof Carbon) {
                    return $date->format('Y-m-d');
                }
                return Carbon::parse($date)->format('Y-m-d');
            } catch (\Throwable $e) {
                return (string)$date;
            }
        };

        return self::streamCsv($filename, $headers, $teachers, function (Teacher $t) use ($formatArray, $formatDate) {
            $school = $t->school;

            // Age and Retirement calculations
            $dob = null;
            $age = '';
            $retirementDate = '';
            $yearsLeft = '';
            if ($t->dateOfBirth) {
                try {
                    $dob = ($t->dateOfBirth instanceof Carbon) ? $t->dateOfBirth : Carbon::parse($t->dateOfBirth);
                    $age = $dob->age;
                    $retireObj = $dob->copy()->addYears(60);
                    $retirementDate = $retireObj->format('Y-m-d');
                    $diffY = 60 - $dob->diffInYears(now());
                    $yearsLeft = max(0, number_format($diffY, 1, '.', ''));
                } catch (\Throwable $e) {}
            }

            // Years at school calculation
            $schoolApptDate = $t->schappoint;
            $yearsAtSchool = '';
            if ($schoolApptDate) {
                try {
                    $sDate = ($schoolApptDate instanceof Carbon) ? $schoolApptDate : Carbon::parse($schoolApptDate);
                    $yearsAtSchool = number_format($sDate->diffInYears(now()), 1, '.', '');
                } catch (\Throwable $e) {}
            }

            // Attached institution name lookup
            $attachedInstName = '';
            if ($t->otherschool_census) {
                $attachedInstName = TeacherOptions::institutionName($t->otherschool_census);
            }

            return [
                $t->id,
                $school->censusNo ?? $t->census_no ?? '',
                $school->name ?? '',
                $school->eduDivision ?? '',
                $school->dsDivision ?? '',
                $school->gnDivision ?? '',
                $school->bilingual_difficulty ?? $school->difficultyCategory ?? '',
                $t->census_no ?? '',
                $t->title ?? '',
                $t->fullName ?? '',
                $t->nic ?? '',
                $t->paysheet ?? '',
                $t->personal_file_no ?? '',
                $formatDate($t->dateOfBirth),
                $age,
                $t->gender ?? '',
                $t->ethnicity ?? '',
                $t->ethnicity_other ?? '',
                $t->religion ?? '',
                $t->religion_other ?? '',
                $t->maritalStatus ?? '',
                $t->marital_other ?? '',
                $t->address ?? '',
                $t->phone ?? '',
                $t->mobile ?? '',
                $t->whatsapp ?? '',
                $t->email ?? '',
                $t->eduqua ?? '',
                $t->subject_stream ?? '',
                $t->field_study ?? '',
                $t->field_study_other ?? '',
                $t->profqua ?? '',
                $t->profqua_other ?? '',
                $t->profall ?? '',
                $t->service ?? '',
                $formatDate($t->firstappoint),
                $t->class_grade ?? '',
                $formatDate($t->clsappoint),
                $formatDate($schoolApptDate),
                $yearsAtSchool,
                $retirementDate,
                $yearsLeft,
                $t->appt_section ?? '',
                $t->appt_category ?? '',
                $t->appt_code ?? '',
                $t->appt_medium ?? '',
                $t->mainduty ?? '',
                $t->mainduty_other ?? '',
                $t->secondduty ?? '',
                $t->secondduty_other ?? '',
                $t->t1_section ?? '',
                $t->t1_category ?? '',
                $t->t1_code ?? '',
                $t->t1_medium ?? '',
                $t->t1_periods !== null ? (string)$t->t1_periods : '',
                $t->t2_section ?? '',
                $t->t2_category ?? '',
                $t->t2_code ?? '',
                $t->t2_medium ?? '',
                $t->t2_periods !== null ? (string)$t->t2_periods : '',
                $t->able1_section ?? '',
                $t->able1_category ?? '',
                $t->able1_code ?? '',
                $t->able1_medium ?? '',
                $t->able2_section ?? '',
                $t->able2_category ?? '',
                $t->able2_code ?? '',
                $t->able2_medium ?? '',
                $t->otherschool ?? '',
                $t->otherschool_census ?? '',
                $attachedInstName,
                $t->otherschool_days ?? '',
                $t->position ?? '',
                $t->salaryby ?? '',
                $t->servicebased ?? '',
                $t->salarysource ?? '',
                $formatDate($t->createdAt),
                $formatDate($t->updatedAt)
            ];
        });
    }

    /**
     * Export buildings dataset with ALL collected database columns.
     */
    public static function exportBuildings(iterable $buildings, ?string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Wilgamuwa_Buildings_Full_Database_Export_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Building ID',
            'School ID',
            'School Census No',
            'School Name',
            'Education Division',
            'DS Division (DSD)',
            'GN Division (GND)',
            'School Difficulty Category',
            'School Address',
            'Building Name',
            'Building Category / Type',
            'Length (Feet)',
            'Width (Feet)',
            'Area (Sq. Ft.)',
            'Dimensions / Size String',
            'Number of Floors',
            'Construction Year',
            'Building Age (Years)',
            'Last Repair / Renovation Year',
            'Years Since Last Renovation',
            'Needs Renovation (>=10 Yrs)',
            'Structural Condition / Status (Bilingual)',
            'Structural Condition / Status (Raw)',
            'Notes / Remarks',
            'School Available Sanitation Units',
            'School Boys Toilets',
            'School Girls Toilets',
            'School Staff Toilets',
            'School Sanitation Norm Required',
            'Building Record Created At',
            'Building Record Last Updated At'
        ];

        return self::streamCsv($filename, $headers, $buildings, function (Building $b) {
            $school = $b->school;
            $sanitation = $school ? $school->sanitation : null;

            $age = $b->age !== null ? (string)$b->age : 'N/A';
            $yearsSince = $b->years_since_repair !== null ? (string)$b->years_since_repair : 'Never / Unknown';
            $needsRenov = $b->needs_renovation ? 'Yes' : 'No';

            return [
                $b->id,
                $b->schoolId,
                $school->censusNo ?? 'N/A',
                $school->name ?? 'Unknown',
                $school->eduDivision ?? 'Wilgamuwa',
                $school->dsDivision ?? '',
                $school->gnDivision ?? '',
                $school->bilingual_difficulty ?? $school->difficultyCategory ?? '',
                $school->address ?? '',
                $b->buildingName,
                $b->buildingType ?? 'Learning / ඉගෙනුම්',
                $b->lengthFeet !== null ? (string)$b->lengthFeet : '',
                $b->widthFeet !== null ? (string)$b->widthFeet : '',
                $b->areaSqft !== null ? (string)$b->areaSqft : '',
                $b->size ?? 'N/A',
                $b->floors ?? 1,
                $b->constructionYear ?? 'N/A',
                $age,
                $b->lastRepairYear ?? 'N/A',
                $yearsSince,
                $needsRenov,
                $b->bilingual_status ?? $b->status ?? '',
                $b->status ?? '',
                $b->notes ?? '',
                $sanitation ? (string)$sanitation->total_toilets : '0',
                $sanitation ? (string)$sanitation->boysToilets : '0',
                $sanitation ? (string)$sanitation->girlsToilets : '0',
                $sanitation ? (string)$sanitation->teacherToilets : '0',
                $sanitation ? (string)$sanitation->total_required_toilets : '0',
                $b->createdAt ? Carbon::parse($b->createdAt)->format('Y-m-d H:i:s') : '',
                $b->updatedAt ? Carbon::parse($b->updatedAt)->format('Y-m-d H:i:s') : ''
            ];
        });
    }

    /**
     * Export school directory dataset with ALL collected database columns.
     */
    public static function exportSchools(iterable $schools, ?string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Wilgamuwa_Schools_Master_Export_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'School ID',
            'Census No',
            'School Name',
            'School Name (Sinhala)',
            'School Category',
            'School Type',
            'Education Zone',
            'Education Division',
            'DS Division (DSD)',
            'GN Division (GND)',
            'School Difficulty Category',
            'School Address',
            'School Land Area',
            'Latitude',
            'Longitude',
            'Contact Phone Number',
            'Official Email Address',
            'Lowest Grade Offered',
            'Highest Grade Offered',
            'Sanctioned Teacher Positions',
            'Actual Teachers Employed',
            'Teacher Cadre Deficit / Surplus',
            'Principal Name',
            'Principal NIC',
            'Principal Mobile Number',
            'Total Enrolled Students',
            'Total Male Students',
            'Total Female Students',
            'Total Class Units',
            'Total School Buildings Count',
            'Total Building Area (Sq. Ft.)',
            'Total Sanitation Units',
            'Boys Toilets',
            'Girls Toilets',
            'Staff Toilets',
            'Sanitation Norm Required',
            'School Active Status',
            'Record Created At',
            'Record Last Updated At'
        ];

        return self::streamCsv($filename, $headers, $schools, function (School $school) {
            $sanitation = $school->sanitation;
            $teachersCount = $school->teachers_count ?? $school->teachers()->count();
            $sanctioned = (int)($school->sanctionedPositions ?? 0);
            $cadreDiff = $sanctioned > 0 ? ($sanctioned - $teachersCount) : 'N/A';

            // Student aggregates
            $currentYear = date('Y');
            $academicYear = $currentYear . '/' . ($currentYear + 1);
            $counts = $school->studentCounts;
            if ($counts->isEmpty()) {
                $counts = $school->studentCounts()->where('academicYear', $academicYear)->get();
            }
            $totMale = (int)$counts->sum('maleCount');
            $totFemale = (int)$counts->sum('femaleCount');
            $totStudents = $totMale + $totFemale;
            $totClasses = (int)$counts->sum('classCount');

            // Buildings aggregates
            $buildings = $school->buildings;
            $totBuildings = $buildings->count();
            $totBuildingArea = (float)$buildings->sum('areaSqft');

            return [
                $school->id,
                $school->censusNo ?? '',
                $school->name,
                $school->nameSinhala ?? '',
                $school->category ?? '',
                $school->schoolType ?? '',
                $school->eduZone ?? 'Wilgamuwa',
                $school->eduDivision ?? '',
                $school->dsDivision ?? '',
                $school->gnDivision ?? '',
                $school->bilingual_difficulty ?? $school->difficultyCategory ?? '',
                $school->address ?? '',
                $school->landArea ?? '',
                $school->latitude !== null ? (string)$school->latitude : '',
                $school->longitude !== null ? (string)$school->longitude : '',
                $school->contactNumber ?? '',
                $school->email ?? '',
                $school->gradeStart !== null ? (string)$school->gradeStart : '',
                $school->gradeEnd !== null ? (string)$school->gradeEnd : '',
                $sanctioned > 0 ? (string)$sanctioned : '',
                (string)$teachersCount,
                (string)$cadreDiff,
                $school->principalName ?? '',
                $school->principalNic ?? '',
                $school->principalMobile ?? '',
                (string)$totStudents,
                (string)$totMale,
                (string)$totFemale,
                (string)$totClasses,
                (string)$totBuildings,
                $totBuildingArea > 0 ? (string)$totBuildingArea : '0',
                $sanitation ? (string)$sanitation->total_toilets : '0',
                $sanitation ? (string)$sanitation->boysToilets : '0',
                $sanitation ? (string)$sanitation->girlsToilets : '0',
                $sanitation ? (string)$sanitation->teacherToilets : '0',
                $sanitation ? (string)$sanitation->total_required_toilets : '0',
                $school->isActive ? 'Active' : 'Inactive',
                $school->createdAt ? Carbon::parse($school->createdAt)->format('Y-m-d H:i:s') : '',
                $school->updatedAt ? Carbon::parse($school->updatedAt)->format('Y-m-d H:i:s') : ''
            ];
        });
    }

    /**
     * Export student overview dataset with ALL collected database columns + Grade 1-13 breakdown.
     */
    public static function exportStudentOverview(iterable $schools, string $academicYear, ?string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Wilgamuwa_Students_Enrollment_All_Grades_' . str_replace('/', '-', $academicYear) . '_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'School ID',
            'Census No',
            'School Name',
            'School Name (Sinhala)',
            'School Category',
            'School Type',
            'Education Division',
            'DS Division (DSD)',
            'GN Division (GND)',
            'School Difficulty Category',
            'School Land Area',
            'School Address',
            'Principal Name',
            'Principal Mobile Number',
            'Academic Year',
            'Total Enrolled Students',
            'Total Male Students',
            'Total Female Students',
            'Total Class Units',
            // Primary Section (Grades 1-5)
            'Primary Section Total Students',
            'Primary Section Classes',
            'Grade 1 Male', 'Grade 1 Female', 'Grade 1 Total', 'Grade 1 Classes',
            'Grade 2 Male', 'Grade 2 Female', 'Grade 2 Total', 'Grade 2 Classes',
            'Grade 3 Male', 'Grade 3 Female', 'Grade 3 Total', 'Grade 3 Classes',
            'Grade 4 Male', 'Grade 4 Female', 'Grade 4 Total', 'Grade 4 Classes',
            'Grade 5 Male', 'Grade 5 Female', 'Grade 5 Total', 'Grade 5 Classes',
            // Junior Secondary (Grades 6-11)
            'Junior Secondary Total Students',
            'Junior Secondary Classes',
            'Grade 6 Male', 'Grade 6 Female', 'Grade 6 Total', 'Grade 6 Classes',
            'Grade 7 Male', 'Grade 7 Female', 'Grade 7 Total', 'Grade 7 Classes',
            'Grade 8 Male', 'Grade 8 Female', 'Grade 8 Total', 'Grade 8 Classes',
            'Grade 9 Male', 'Grade 9 Female', 'Grade 9 Total', 'Grade 9 Classes',
            'Grade 10 Male', 'Grade 10 Female', 'Grade 10 Total', 'Grade 10 Classes',
            'Grade 11 Male', 'Grade 11 Female', 'Grade 11 Total', 'Grade 11 Classes',
            // Senior Secondary (Grades 12-13)
            'Senior Secondary Total Students',
            'Senior Secondary Classes',
            'Grade 12 Male', 'Grade 12 Female', 'Grade 12 Total', 'Grade 12 Classes',
            'Grade 13 Male', 'Grade 13 Female', 'Grade 13 Total', 'Grade 13 Classes',
            // Infrastructure summary
            'Total Buildings Count',
            'Available Sanitation Units',
            'Boys Toilets',
            'Girls Toilets',
            'Staff Toilets',
            'Sanitation Norm Required',
            'Active Status'
        ];

        $censusByNo = collect(\App\Services\StudentCensusService::getSchoolsCensus())->keyBy('census_no');
        $allCensus = \App\Services\StudentCensusService::getSchoolsCensus();

        return self::streamCsv($filename, $headers, $schools, function (School $school) use ($academicYear, $censusByNo, $allCensus) {
            $counts = $school->studentCounts;
            $sanitation = $school->sanitation;

            $cData = $censusByNo->get($school->censusNo)
                ?: $censusByNo->get(str_pad($school->censusNo ?? '', 5, '0', STR_PAD_LEFT));

            if (!$cData && $school->name) {
                $cleanName = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $school->name));
                foreach ($allCensus as $scItem) {
                    $cleanCName = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $scItem['name_en'] ?? ''));
                    if ($cleanName === $cleanCName || str_contains($cleanName, $cleanCName) || str_contains($cleanCName, $cleanName)) {
                        $cData = $scItem;
                        break;
                    }
                }
            }

            // Map by grade string or number
            $gradeMap = [];
            foreach ($counts as $sc) {
                $rawG = (string)$sc->grade;
                $numG = preg_replace('/[^0-9]/', '', $rawG);
                $info = [
                    'm' => (int)$sc->maleCount,
                    'f' => (int)$sc->femaleCount,
                    't' => (int)$sc->maleCount + (int)$sc->femaleCount,
                    'c' => (int)$sc->classCount,
                ];
                $gradeMap[$rawG] = $info;
                if ($numG !== '') {
                    $gradeMap[$numG] = $info;
                }
            }

            $getG = function ($grade) use ($gradeMap, $cData) {
                $key = (string)$grade;
                $strKey = 'Grade ' . $grade;
                if (isset($gradeMap[$key])) return $gradeMap[$key];
                if (isset($gradeMap[$strKey])) return $gradeMap[$strKey];
                if ($cData && isset($cData['grades'][$strKey])) {
                    $cg = $cData['grades'][$strKey];
                    return [
                        'm' => (int)($cg['male'] ?? 0),
                        'f' => (int)($cg['female'] ?? 0),
                        't' => (int)($cg['total'] ?? 0),
                        'c' => (int)($cg['classes'] ?? 0),
                    ];
                }
                return ['m' => 0, 'f' => 0, 't' => 0, 'c' => 0];
            };

            // Calculate Primary Section (1-5)
            $primM = 0; $primF = 0; $primT = 0; $primC = 0;
            for ($i = 1; $i <= 5; $i++) {
                $g = $getG($i);
                $primM += $g['m']; $primF += $g['f']; $primT += $g['t']; $primC += $g['c'];
            }

            // Calculate Junior Secondary (6-11)
            $junM = 0; $junF = 0; $junT = 0; $junC = 0;
            for ($i = 6; $i <= 11; $i++) {
                $g = $getG($i);
                $junM += $g['m']; $junF += $g['f']; $junT += $g['t']; $junC += $g['c'];
            }

            // Calculate Senior Secondary (12-13)
            $senM = 0; $senF = 0; $senT = 0; $senC = 0;
            for ($i = 12; $i <= 13; $i++) {
                $g = $getG($i);
                $senM += $g['m']; $senF += $g['f']; $senT += $g['t']; $senC += $g['c'];
            }

            $totalM = (int)$counts->sum('maleCount');
            $totalF = (int)$counts->sum('femaleCount');
            $totalStudents = $totalM + $totalF;
            $totalClasses = (int)$counts->sum('classCount');

            if ($totalStudents == 0 && $cData) {
                $totalM = (int)($cData['grand_total']['male'] ?? 0);
                $totalF = (int)($cData['grand_total']['female'] ?? 0);
                $totalStudents = $totalM + $totalF;
                $totalClasses = (int)($cData['total_classes'] ?? 0);
            }

            $row = [
                $school->id,
                $school->censusNo ?? '',
                $school->name,
                $school->nameSinhala ?? '',
                $school->category ?? '',
                $school->schoolType ?? '',
                $school->eduDivision ?? '',
                $school->dsDivision ?? '',
                $school->gnDivision ?? '',
                $school->bilingual_difficulty ?? $school->difficultyCategory ?? '',
                $school->landArea ?? '',
                $school->address ?? '',
                $school->principalName ?? '',
                $school->principalMobile ?? '',
                $academicYear,
                (string)$totalStudents,
                (string)$totalM,
                (string)$totalF,
                (string)$totalClasses,
                // Primary
                (string)$primT,
                (string)$primC,
            ];

            // Add Grade 1-5 details
            for ($i = 1; $i <= 5; $i++) {
                $g = $getG($i);
                $row[] = (string)$g['m'];
                $row[] = (string)$g['f'];
                $row[] = (string)$g['t'];
                $row[] = (string)$g['c'];
            }

            // Junior Secondary
            $row[] = (string)$junT;
            $row[] = (string)$junC;
            for ($i = 6; $i <= 11; $i++) {
                $g = $getG($i);
                $row[] = (string)$g['m'];
                $row[] = (string)$g['f'];
                $row[] = (string)$g['t'];
                $row[] = (string)$g['c'];
            }

            // Senior Secondary
            $row[] = (string)$senT;
            $row[] = (string)$senC;
            for ($i = 12; $i <= 13; $i++) {
                $g = $getG($i);
                $row[] = (string)$g['m'];
                $row[] = (string)$g['f'];
                $row[] = (string)$g['t'];
                $row[] = (string)$g['c'];
            }

            // Infrastructure
            $row[] = (string)$school->buildings()->count();
            $row[] = $sanitation ? (string)$sanitation->total_toilets : '0';
            $row[] = $sanitation ? (string)$sanitation->boysToilets : '0';
            $row[] = $sanitation ? (string)$sanitation->girlsToilets : '0';
            $row[] = $sanitation ? (string)$sanitation->teacherToilets : '0';
            $row[] = $sanitation ? (string)$sanitation->total_required_toilets : '0';
            $row[] = $school->isActive ? 'Active' : 'Inactive';

            return $row;
        });
    }

    /**
     * Export OL Subjects matrix to CSV.
     */
    public static function exportOlSubjectsMatrix(array $olData, ?string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Wilgamuwa_OL_Subjects_Matrix_2025_' . date('Y_m_d_His') . '.csv';

        // Extract school names from first row with schools
        $schoolNames = [];
        foreach ($olData as $r) {
            if (!empty($r['schools'])) {
                $schoolNames = array_keys($r['schools']);
                break;
            }
        }

        $headers = [
            'Subject (Sinhala)',
            'Subject (English)',
            'Total Gr 10 Male',
            'Total Gr 10 Female',
            'Total Gr 10 Total',
            'Total Gr 11 Male',
            'Total Gr 11 Female',
            'Total Gr 11 Total',
            'Zonal Total Students',
        ];

        foreach ($schoolNames as $sn) {
            $short = str_replace(['CP/WIL/', 'MAHA VIDYALAYA', 'KANISHTA VIDYALAYA', 'CENTRAL COLLEGE', 'KANISTA VIDIYALAYA', 'KANISTHTA SCHOOL', 'JUNIOR SCHOOL', 'K.V.'], '', $sn);
            $headers[] = trim($short) . ' (Gr 10)';
            $headers[] = trim($short) . ' (Gr 11)';
            $headers[] = trim($short) . ' (Total)';
        }

        return self::streamCsv($filename, $headers, $olData, function ($item) use ($schoolNames) {
            $tot = $item['total'] ?? [];
            $row = [
                $item['subject'] ?? '',
                $item['subject_en'] ?? ($item['subject'] ?? ''),
                (string)($tot['grade_10']['male'] ?? 0),
                (string)($tot['grade_10']['female'] ?? 0),
                (string)($tot['grade_10']['total'] ?? 0),
                (string)($tot['grade_11']['male'] ?? 0),
                (string)($tot['grade_11']['female'] ?? 0),
                (string)($tot['grade_11']['total'] ?? 0),
                (string)($tot['all']['total'] ?? 0),
            ];

            foreach ($schoolNames as $sn) {
                $sData = $item['schools'][$sn] ?? [];
                $row[] = (string)($sData['grade_10']['total'] ?? 0);
                $row[] = (string)($sData['grade_11']['total'] ?? 0);
                $row[] = (string)($sData['all']['total'] ?? 0);
            }

            return $row;
        });
    }

    /**
     * Export AL Subjects matrix to CSV.
     */
    public static function exportAlSubjectsMatrix(array $alData, ?string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Wilgamuwa_AL_Subjects_Matrix_2025_' . date('Y_m_d_His') . '.csv';

        $schoolNames = [];
        foreach ($alData as $r) {
            if (!empty($r['schools'])) {
                $schoolNames = array_keys($r['schools']);
                break;
            }
        }

        $headers = [
            'Subject (Sinhala)',
            'Subject (English)',
            'Total Gr 12 Male',
            'Total Gr 12 Female',
            'Total Gr 12 Total',
            'Total Gr 13 Male',
            'Total Gr 13 Female',
            'Total Gr 13 Total',
            'Zonal Total Students',
        ];

        foreach ($schoolNames as $sn) {
            $short = str_replace(['CP/WIL/', 'MAHA VIDYALAYA', 'CENTRAL COLLEGE'], '', $sn);
            $headers[] = trim($short) . ' (Gr 12)';
            $headers[] = trim($short) . ' (Gr 13)';
            $headers[] = trim($short) . ' (Total)';
        }

        return self::streamCsv($filename, $headers, $alData, function ($item) use ($schoolNames) {
            $tot = $item['total'] ?? [];
            $row = [
                $item['subject'] ?? '',
                $item['subject_en'] ?? ($item['subject'] ?? ''),
                (string)($tot['grade_12']['male'] ?? 0),
                (string)($tot['grade_12']['female'] ?? 0),
                (string)($tot['grade_12']['total'] ?? 0),
                (string)($tot['grade_13']['male'] ?? 0),
                (string)($tot['grade_13']['female'] ?? 0),
                (string)($tot['grade_13']['total'] ?? 0),
                (string)($tot['all']['total'] ?? 0),
            ];

            foreach ($schoolNames as $sn) {
                $sData = $item['schools'][$sn] ?? [];
                $row[] = (string)($sData['grade_12']['total'] ?? 0);
                $row[] = (string)($sData['grade_13']['total'] ?? 0);
                $row[] = (string)($sData['all']['total'] ?? 0);
            }

            return $row;
        });
    }

    /**
     * Export AL Streams matrix to CSV.
     */
    public static function exportAlStreamsMatrix(array $streamData, ?string $filename = null): StreamedResponse
    {
        $filename = $filename ?: 'Wilgamuwa_AL_Streams_Matrix_2025_' . date('Y_m_d_His') . '.csv';

        $schoolNames = [];
        foreach ($streamData as $r) {
            if (!empty($r['schools'])) {
                $schoolNames = array_keys($r['schools']);
                break;
            }
        }

        $headers = [
            'Stream (Sinhala)',
            'Stream (English)',
            'Total Gr 12 Male',
            'Total Gr 12 Female',
            'Total Gr 12 Total',
            'Total Gr 13 Male',
            'Total Gr 13 Female',
            'Total Gr 13 Total',
            'Zonal Total Students',
        ];

        foreach ($schoolNames as $sn) {
            $short = str_replace(['CP/WIL/', 'MAHA VIDYALAYA', 'CENTRAL COLLEGE'], '', $sn);
            $headers[] = trim($short) . ' (Gr 12)';
            $headers[] = trim($short) . ' (Gr 13)';
            $headers[] = trim($short) . ' (Total)';
        }

        return self::streamCsv($filename, $headers, $streamData, function ($item) use ($schoolNames) {
            $tot = $item['total'] ?? [];
            $row = [
                $item['stream'] ?? '',
                $item['stream_en'] ?? ($item['stream'] ?? ''),
                (string)($tot['grade_12']['male'] ?? 0),
                (string)($tot['grade_12']['female'] ?? 0),
                (string)($tot['grade_12']['total'] ?? 0),
                (string)($tot['grade_13']['male'] ?? 0),
                (string)($tot['grade_13']['female'] ?? 0),
                (string)($tot['grade_13']['total'] ?? 0),
                (string)($tot['all']['total'] ?? 0),
            ];

            foreach ($schoolNames as $sn) {
                $sData = $item['schools'][$sn] ?? [];
                $row[] = (string)($sData['grade_12']['total'] ?? 0);
                $row[] = (string)($sData['grade_13']['total'] ?? 0);
                $row[] = (string)($sData['all']['total'] ?? 0);
            }

            return $row;
        });
    }
}
