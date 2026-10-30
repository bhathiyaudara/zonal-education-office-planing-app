<?php

namespace App\Services;

use App\Models\School;
use App\Models\StudentCount;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StudentCensusService
{
    protected static array $olSubjectTranslations = [
        'භූගෝල විද්‍යාව' => 'Geography',
        'පුරවැසි අධ්‍යාපනය' => 'Citizenship Education',
        'ව්‍යාපර අධ්‍යයනය හා ගිණුම්කරණ අධ්‍යයනය' => 'Business & Accounting',
        'ව්‍යවසායකත්ව අධ්‍යයනය' => 'Entrepreneurship Studies',
        'දෙවන භාෂාව (දෙමළ)' => 'Second Language (Tamil)',
        'පෙරදිග සංගීතය' => 'Eastern Music',
        'චිත්‍ර' => 'Art',
        'නර්තනය' => 'Dancing',
        'නාට්‍ය හා රංග කලාව' => 'Drama & Theatre',
        'තොරතුරු තාක්ෂණය' => 'Information Technology (ICT)',
        'කෘෂි හා ආහාර තාක්ෂණය' => 'Agriculture & Food Technology',
        'ගෘහ ආර්ථික විද්‍යාව' => 'Home Economics',
        'ප්‍රා.තා.කු' => 'Practical Technical Skills',
        'සෞඛ්‍ය හා ශාරීරික අධ්‍යාපනය' => 'Health & Physical Education',
        'නිර්මාණකරණය සහ ඉදිකිරිමී තාක්ෂණය' => 'Design & Construction Tech',
        'සිංහල රසාස්වාදය' => 'Sinhala Literature',
        'සන්නිවේදන හා මාධ්‍ය අධ්‍යයනය' => 'Communication & Media',
    ];

    protected static array $alSubjectTranslations = [
        'භෞතික විද්‍යාව' => 'Physics',
        'රසායන විද්‍යාව' => 'Chemistry',
        'ගණිතය' => 'Mathematics',
        'කෘෂි විද්‍යාව' => 'Agricultural Science',
        'ජීව විද්‍යාව' => 'Biology',
        'සංයුක්ත ගණිතය' => 'Combined Mathematics',
        'උසස් ගණිතය' => 'Higher Mathematics',
        'තාක්ෂණය' => 'Technology',
        'යාන්ත්‍රික තාක්ෂණය' => 'Mechanical Technology',
        'තොරතුරු තාක්ෂණය' => 'Information Technology (ICT)',
        'ආහාර තාක්ෂණය' => 'Food Technology',
        'කෘෂි තාක්ෂණය' => 'Agro Technology',
        'ජීව සම්පත් තාක්ෂණය' => 'Bio-Resource Technology',
        'ආර්ථික විද්‍යාව' => 'Economics',
        'භූගෝල විද්‍යාව' => 'Geography',
        'දේශපාලන විද්‍යාව' => 'Political Science',
        'තර්ක විද්‍යාව හා විද්‍යාත්මක ක්‍රමය' => 'Logic & Scientific Method',
        'ඉතිහාසය (ඉන්දියානු)' => 'Indian History',
        'ඉතිහාසය (යුරෝපීය)' => 'European History',
        'ගෘහ ආර්ථික විද්‍යාව' => 'Home Economics',
        'ව්‍යාපාර අධ්‍යයනය' => 'Business Studies',
        'ගණකාධිකරණය' => 'Accounting',
        'ව්‍යාපාර සංඛ්‍යානය' => 'Business Statistics',
        'බුද්ධාගම' => 'Buddhism',
        'හින්දු ආගම' => 'Hinduism',
        'ක්‍රිස්තියානි ආගම' => 'Christianity',
        'ඉස්ලාම් ආගම' => 'Islam',
        'බෞද්ධ ශිෂ්ටාචාරය' => 'Buddhist Civilization',
        'හින්දු ශිෂ්ටාචාරය' => 'Hindu Civilization',
        'ඉස්ලාම් ශිෂ්ටාචාරය' => 'Islamic Civilization',
        'ග්‍රීක සහ රෝම ශිෂ්ටාචාරය' => 'Greek & Roman Civ.',
        'චිත්‍ර' => 'Art',
        'නැටුම් (සිංහල)' => 'Sinhala Dancing',
        'නැටුම් (භරත)' => 'Bharata Dancing',
        'සංගීතය (පෙරදිග)' => 'Eastern Music',
        'සංගීතය (කර්ණාටක)' => 'Carnatic Music',
        'සංගීතය (බටහිර)' => 'Western Music',
        'නාට්‍ය හා රංග කලාව (සිංහල)' => 'Sinhala Drama',
        'නාට්‍ය හා රංග කලාව (දෙමළ)' => 'Tamil Drama',
        'නාට්‍ය හා රංග කලාව (ඉංග්‍රීසි)' => 'English Drama',
        'සිංහල' => 'Sinhala',
        'දෙමළ' => 'Tamil',
        'ඉංග්‍රීසි' => 'English',
        'පාලි' => 'Pali',
        'සංස්කෘත' => 'Sanskrit',
        'තාක්ෂණය සදහා විද්‍යාව' => 'Science for Tech (SFT)',
        'ජෛව පද්ධති තාක්ෂණය' => 'Biosystems Tech (BST)',
        'ඉංජිනේරු තාක්ෂණවේදය' => 'Engineering Tech (ET)',
        'සන්නිවේදන හා මාධ්‍ය අධ්‍යයනය' => 'Communication & Media',
    ];

    protected static array $streamTranslations = [
        'කලා' => 'Arts (කලා)',
        'වාණිජ' => 'Commerce (වාණිජ)',
        'විද්‍යා' => 'Biological Science (විද්‍යා)',
        'ගණිත' => 'Physical Science / Maths (ගණිත)',
        'තාක්ෂණවේදය' => 'Technology (තාක්ෂණවේදය)',
    ];

    /**
     * Get JSON data from file.
     */
    protected static function loadJson(string $fileName): array
    {
        $path = database_path("data/{$fileName}");
        if (File::exists($path)) {
            $content = File::get($path);
            return json_decode($content, true) ?: [];
        }
        return [];
    }

    /**
     * Get schools enrollment census data.
     */
    public static function getSchoolsCensus(): array
    {
        return self::loadJson('students_census_2025.json');
    }

    /**
     * Get Pirivena census data.
     */
    public static function getPirivenaCensus(): array
    {
        return self::loadJson('pirivena_census_2025.json');
    }

    /**
     * Get OL subjects statistics.
     */
    public static function getOlSubjects(): array
    {
        $data = self::loadJson('ol_subjects_2025.json');
        foreach ($data as &$row) {
            $s = $row['subject'];
            $row['subject_en'] = self::$olSubjectTranslations[$s] ?? $s;
        }
        return $data;
    }

    /**
     * Get AL subjects statistics.
     */
    public static function getAlSubjects(): array
    {
        $data = self::loadJson('al_subjects_2025.json');
        foreach ($data as &$row) {
            $s = $row['subject'];
            $row['subject_en'] = self::$alSubjectTranslations[$s] ?? $s;
        }
        return $data;
    }

    /**
     * Get AL streams statistics.
     */
    public static function getAlStreams(): array
    {
        $data = self::loadJson('al_streams_2025.json');
        foreach ($data as &$row) {
            $s = $row['stream'];
            $row['stream_en'] = self::$streamTranslations[$s] ?? $s;
        }
        return $data;
    }

    /**
     * Get Zonal High-level KPI aggregates.
     */
    public static function getZonalKPIs(): array
    {
        $schools = self::getSchoolsCensus();
        $pirivenas = self::getPirivenaCensus();

        $totalStudents = 0;
        $totalMale = 0;
        $totalFemale = 0;
        $totalClasses = 0;
        $primaryStudents = 0;
        $secondaryStudents = 0;
        $collegiateStudents = 0;
        $specialEdStudents = 0;
        $wilgamuwaStudents = 0;
        $laggalaStudents = 0;

        foreach ($schools as $s) {
            $pri = $s['primary_total']['total'] ?? 0;
            $sec = $s['secondary_total']['total'] ?? 0;
            $col = $s['collegiate_total']['total'] ?? 0;
            $spe = $s['special_ed']['total'] ?? 0;
            
            $primaryStudents += $pri;
            $secondaryStudents += $sec;
            $collegiateStudents += $col;
            $specialEdStudents += $spe;

            $m = $s['grand_total']['male'] ?? 0;
            $f = $s['grand_total']['female'] ?? 0;
            $tot = $s['grand_total']['total'] ?? ($m + $f);

            $totalMale += $m;
            $totalFemale += $f;
            $totalStudents += $tot;
            $totalClasses += ($s['total_classes'] ?? 0);

            if (($s['division'] ?? '') === 'Wilgamuwa') {
                $wilgamuwaStudents += $tot;
            } elseif (($s['division'] ?? '') === 'Laggala') {
                $laggalaStudents += $tot;
            }
        }

        $pirivenaTotal = array_sum(array_column($pirivenas, 'total'));
        $pirivenaTeachers = array_sum(array_column($pirivenas, 'teachers'));

        return [
            'totalStudents' => $totalStudents,
            'totalMale' => $totalMale,
            'totalFemale' => $totalFemale,
            'totalSchools' => count($schools),
            'totalClasses' => $totalClasses,
            'primaryStudents' => $primaryStudents,
            'secondaryStudents' => $secondaryStudents,
            'collegiateStudents' => $collegiateStudents,
            'specialEdStudents' => $specialEdStudents,
            'wilgamuwaStudents' => $wilgamuwaStudents,
            'laggalaStudents' => $laggalaStudents,
            'pirivenaTotal' => $pirivenaTotal,
            'pirivenaTeachers' => $pirivenaTeachers,
            'grandZonalTotal' => $totalStudents + $pirivenaTotal,
        ];
    }
}
