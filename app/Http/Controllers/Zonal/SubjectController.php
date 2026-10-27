<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $subjects = $query->orderBy('name')->get();

        return view('dashboard.zonal.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191|unique:subject,name',
            'nameSinhala' => 'nullable|string|max:191',
            'category' => 'nullable|string|max:191',
        ]);

        Subject::create($validated);
        return back()->with('success', 'Subject added successfully!');
    }

    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:191|unique:subject,name,' . $id,
            'nameSinhala' => 'nullable|string|max:191',
            'category' => 'nullable|string|max:191',
        ]);

        $subject->update($validated);
        return back()->with('success', 'Subject updated!');
    }

    public function destroy(string $id)
    {
        Subject::findOrFail($id)->delete();
        return back()->with('success', 'Subject removed.');
    }
}
