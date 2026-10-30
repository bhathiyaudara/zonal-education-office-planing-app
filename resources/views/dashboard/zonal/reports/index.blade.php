@extends('layouts.dashboard')

@section('title', 'Reports & Exports')

@section('content')
<div class="space-y-6 animate-fade-in-up">
    {{-- Gradient Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10 shrink-0 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="zems-header-title">Reports & Data Exports</h1>
                <p class="zems-header-subtitle">Generate official CSV and spreadsheet records with all database columns for zone planning, infrastructure, and cadre</p>
            </div>
        </div>
    </div>

    {{-- Report Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Report 1: Teachers Master Directory --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-purple-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">Teachers Master Database Directory</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Complete master dataset of all teachers including NIC, service details, qualifications, appointment subjects, duties, years in current school, and retirement timeline.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">All 70+ Columns</span>
                <a href="{{ route('zonal.teachers.export') }}" class="zems-btn-primary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export All Pages
                </a>
            </div>
        </div>

        {{-- Report 2: All Zone School Buildings --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-blue-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">School Buildings Master Directory</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Complete physical infrastructure inventory of all school buildings, halls, dimensions (L&times;W), floor counts, construction years, and structural conditions.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">All Buildings</span>
                <a href="{{ route('zonal.buildings.export') }}" class="zems-btn-primary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export All Pages
                </a>
            </div>
        </div>

        {{-- Report 3: Buildings Needing Renovation --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-amber-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">Buildings Needing Renovation (&ge;10 Yrs)</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Priority list of school buildings across Wilgamuwa Zone that have not undergone renovation in the last 10 years, including condition status and dimensions.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Priority List</span>
                <a href="{{ route('zonal.buildings.export', ['renovation_needed' => 1]) }}" class="zems-btn-primary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export All Pages
                </a>
            </div>
        </div>

        {{-- Report 4: Student Enrollment Detailed Report --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-emerald-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">Student Enrollment & Grade Breakdown</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Detailed census of student enrollments with Grade 1 to 13 male, female, total, class units, and section summaries (Primary, Junior, Senior) across all schools.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Grades 1-13</span>
                <a href="{{ route('zonal.schools.students.export') }}" class="zems-btn-primary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export All Pages
                </a>
            </div>
        </div>

        {{-- Report 5: Schools Master Profile & Infrastructure --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-indigo-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">Schools Master Profile & Cadre</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Institutional registry with census numbers, contact details, principal contacts, land area, GPS coordinates, cadre statistics, and sanitation units.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">All Schools</span>
                <a href="{{ route('zonal.schools.export') }}" class="zems-btn-primary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export All Pages
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
