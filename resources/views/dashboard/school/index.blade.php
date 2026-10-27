@extends('layouts.dashboard')

@section('title', 'School Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    {{-- Header --}}
    <div class="zems-header">
        <div class="flex items-center justify-between flex-wrap gap-4 w-full">
            <div>
                <h1 class="zems-header-title">{{ $school->name }}</h1>
                <p class="zems-header-subtitle text-emerald-100">School Management Overview</p>
            </div>
            <a href="{{ route('school.teachers') }}" class="zems-btn-secondary !bg-white/15 !text-white !border-white/20 hover:!bg-white/25 backdrop-blur-sm">
                View Staff Directory →
            </a>
        </div>
    </div>

    {{-- School Info Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="zems-card-base p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Census No</h3>
            <p class="text-2xl font-black text-slate-900">{{ $school->censusNo ?: '-' }}</p>
        </div>
        <div class="zems-card-base p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Teachers</h3>
            <p class="text-2xl font-black text-slate-900">{{ $school->teachers->count() }}</p>
        </div>
        <div class="zems-card-base p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Sanctioned Positions</h3>
            <p class="text-2xl font-black text-slate-900">{{ $school->sanctionedPositions }}</p>
        </div>
    </div>

    {{-- School Details Card --}}
    <div class="zems-card-base p-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 class="text-lg font-bold text-slate-900">School Details</h2>
            <a href="{{ route('school.profile.edit') }}" class="zems-btn-secondary py-1.5 px-3 shadow-none text-xs">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                Edit Information
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            @foreach([
                'Address' => $school->address,
                'Contact' => $school->contactNumber,
                'Email' => $school->email,
                'School Type' => $school->schoolType,
                'Grade Range' => $school->gradeStart . ' - ' . $school->gradeEnd,
                'Principal' => $school->principalName,
                'Principal NIC' => $school->principalNic,
                'Principal Mobile' => $school->principalMobile,
            ] as $label => $value)
            <div class="flex items-start gap-3">
                <span class="text-sm font-semibold text-slate-500 w-36 shrink-0">{{ $label }}:</span>
                <span class="text-sm text-slate-800">{{ $value ?: '-' }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Student Counts --}}
    <div class="zems-card-base p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">Student Counts ({{ date('Y') }})</h2>
            <a href="{{ route('school.students') }}" class="zems-btn-secondary py-1.5 px-3 shadow-none text-xs">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                Manage Demographics
            </a>
        </div>
        <div class="zems-table-container">
            <table class="zems-table">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th">Grade</th>
                        <th class="zems-table-th text-center">Classes</th>
                        <th class="zems-table-th text-center !text-blue-500">Male</th>
                        <th class="zems-table-th text-center !text-pink-500">Female</th>
                        <th class="zems-table-th text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($school->studentCounts as $sc)
                    <tr class="zems-table-row">
                        <td class="zems-table-td font-semibold text-slate-700">{{ $sc->grade }}</td>
                        <td class="zems-table-td text-center">
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100">{{ $sc->classCount ?? 0 }}</span>
                        </td>
                        <td class="zems-table-td text-center font-bold text-blue-600">{{ $sc->maleCount }}</td>
                        <td class="zems-table-td text-center font-bold text-pink-600">{{ $sc->femaleCount }}</td>
                        <td class="zems-table-td text-center font-black text-slate-900">{{ $sc->maleCount + $sc->femaleCount }}</td>
                    </tr>
                    @empty
                    <tr class="zems-table-row">
                        <td colspan="5" class="zems-table-td text-center text-slate-500 py-8">
                            No student counts have been recorded for this academic year yet.
                            <br>
                            <a href="{{ route('school.students') }}" class="text-emerald-600 hover:text-emerald-700 font-bold mt-2 inline-block">Manage Demographics →</a>
                        </td>
                    </tr>
                    @endforelse

                    @if($school->studentCounts->count() > 0)
                    <tr class="bg-gradient-to-r from-slate-50 to-white border-t-2 border-slate-200">
                        <td class="zems-table-td text-right text-xs tracking-widest uppercase font-black text-slate-500">TOTAL</td>
                        <td class="zems-table-td text-center">
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-black rounded-lg border border-emerald-100">{{ $school->studentCounts->sum('classCount') }}</span>
                        </td>
                        <td class="zems-table-td text-center text-lg font-black text-blue-600">{{ $school->studentCounts->sum('maleCount') }}</td>
                        <td class="zems-table-td text-center text-lg font-black text-pink-600">{{ $school->studentCounts->sum('femaleCount') }}</td>
                        <td class="zems-table-td text-center">
                            <span class="px-3 py-1 bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-sm font-black rounded-xl text-base">{{ $school->studentCounts->sum('maleCount') + $school->studentCounts->sum('femaleCount') }}</span>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Teachers List --}}
    <div class="zems-card-base p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">Staff ({{ $school->teachers->count() }})</h2>
            <a href="{{ route('school.teachers.create') }}" class="zems-btn-primary !bg-emerald-600 hover:!bg-emerald-700 text-xs px-4 py-2">
                <svg class="w-4 h-4 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Teacher
            </a>
        </div>
        <div class="zems-table-container">
            <table class="zems-table">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th">Name</th>
                        <th class="zems-table-th">NIC</th>
                        <th class="zems-table-th">Subject</th>
                        <th class="zems-table-th">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($school->teachers as $teacher)
                    <tr class="zems-table-row">
                        <td class="zems-table-td font-bold text-slate-900">{{ $teacher->nameWithInitials }}</td>
                        <td class="zems-table-td font-semibold text-slate-600">{{ $teacher->nic }}</td>
                        <td class="zems-table-td text-slate-600">{{ $teacher->appointmentSubject ?: '-' }}</td>
                        <td class="zems-table-td text-slate-600">{{ $teacher->phone ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <p class="text-sm font-black uppercase tracking-wider">No teachers registered yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
