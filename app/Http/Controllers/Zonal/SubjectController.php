<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Services\TeacherOptions;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $rawCodes = TeacherOptions::codes() ?: [];
        $search = strtolower(trim($request->input('search', '')));

        $subjects = collect($rawCodes)->filter(function ($code) use ($search) {
            return empty($search) || stripos($code, $search) !== false;
        })->map(function ($code, $idx) {
            $parts = explode('-', $code, 2);
            $cat = count($parts) > 1 ? trim($parts[0]) : 'General';
            $name = count($parts) > 1 ? trim($parts[1]) : $code;
            return (object)[
                'id' => (string)($idx + 1),
                'name' => $name,
                'nameSinhala' => '',
                'category' => 'Code: ' . $cat,
            ];
        })->values();

        return view('dashboard.zonal.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        return back()->with('success', 'Subject definitions are maintained centrally in the Ministry curriculum configuration.');
    }

    public function update(Request $request, string $id)
    {
        return back()->with('success', 'Subject definitions are maintained centrally in the Ministry curriculum configuration.');
    }

    public function destroy(string $id)
    {
        return back()->with('success', 'Subject definitions are maintained centrally in the Ministry curriculum configuration.');
    }
}
