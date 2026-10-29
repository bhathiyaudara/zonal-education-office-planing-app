<?php

namespace App\Services;

class TeacherOptions
{
    protected static ?array $data = null;

    public static function all(): array
    {
        if (self::$data === null) {
            $path = resource_path('data/teacherOptions.json');
            if (!file_exists($path)) {
                $path = public_path('data/teacherOptions.json');
            }
            if (file_exists($path)) {
                self::$data = json_decode(file_get_contents($path), true) ?? [];
            } else {
                self::$data = [];
            }
            self::$data['educationalOffices'] = self::educationalOffices();
        }
        return self::$data;
    }

    public static function educationalOffices(): array
    {
        return [
            'OFFICE_ZEO'     => 'Zonal Education Office - Wilgamuwa',
            'OFFICE_PDC'     => 'Professional Development Centre for Teachers (PDC)',
            'OFFICE_DEO_WIL' => 'Divisional Education Office - Wilgamuwa',
            'OFFICE_DEO_PAL' => 'Divisional Education Office - Pallegama',
            'OFFICE_ITDLH'   => 'Information Technology and Distance Learning Hub (ITDLH)',
        ];
    }

    public static function institutionName(?string $censusOrCode): string
    {
        if (!$censusOrCode) {
            return '';
        }
        $offices = self::educationalOffices();
        if (isset($offices[$censusOrCode])) {
            return $offices[$censusOrCode];
        }
        try {
            $school = \App\Models\School::where('censusNo', $censusOrCode)->first();
            if ($school) {
                return $school->name;
            }
        } catch (\Throwable $e) {
            // Fallback to JSON options if database is not reachable
        }
        foreach (self::schools() as $s) {
            if (($s['censusNo'] ?? '') === $censusOrCode) {
                return $s['name'] ?? $censusOrCode;
            }
        }
        return $censusOrCode;
    }

    public static function get(string $key, array $default = []): array
    {
        $all = self::all();
        return $all[$key] ?? $default;
    }

    public static function schools(): array
    {
        return self::get('schools');
    }

    public static function titles(): array
    {
        return self::get('q05title');
    }

    public static function positions(): array
    {
        return self::get('q07position');
    }

    public static function salariesBy(): array
    {
        return self::get('q08salaryby');
    }

    public static function serviceBased(): array
    {
        return self::get('q09servicebased');
    }

    public static function salarySources(): array
    {
        return self::get('q10salarysource');
    }

    public static function genders(): array
    {
        return self::get('q11gender');
    }

    public static function ethnicities(): array
    {
        return self::get('q12ethnicity');
    }

    public static function religions(): array
    {
        return self::get('q13religion');
    }

    public static function maritalStatuses(): array
    {
        return self::get('q14marital');
    }

    public static function eduQualifications(): array
    {
        return self::get('q15eduqua');
    }

    public static function subjectStreams(): array
    {
        return self::get('q16subject');
    }

    public static function fields(): array
    {
        return self::get('q17field');
    }

    public static function profQualifications(): array
    {
        return self::get('q18profqua');
    }

    public static function services(): array
    {
        return self::get('q19service');
    }

    public static function classes(): array
    {
        return self::get('q21class');
    }

    public static function sections(): array
    {
        return self::get('q23section');
    }

    public static function categories(): array
    {
        return self::get('q23category');
    }

    public static function codes(): array
    {
        return self::get('q23code');
    }

    public static function mediums(): array
    {
        return self::get('q23medium');
    }

    public static function firstDuties(): array
    {
        return self::get('q24fmostwork');
    }

    public static function secondDuties(): array
    {
        return self::get('q26smostwork');
    }

    public static function profQualificationsPossessed(): array
    {
        return self::get('q30op1');
    }
}
