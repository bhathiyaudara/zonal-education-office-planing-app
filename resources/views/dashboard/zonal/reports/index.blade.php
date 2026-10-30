@extends('layouts.dashboard')

@section('title', 'Reports & Exports')

@section('content')
<div class="space-y-6 animate-fade-in-up">
    {{-- Gradient Header --}}
    <div class="zems-header">
        <div class="flex items-start gap-4">
            <div class="hidden sm:flex w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center border border-white/10 shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="zems-header-title">Reports & Data Exports</h1>
                <p class="zems-header-subtitle">Generate official CSV and spreadsheet records for zone planning, infrastructure and cadre</p>
            </div>
        </div>
    </div>

    {{-- Report Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Report 1: Buildings Needing Renovation --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-amber-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">Buildings Needing Renovation (&ge;10 Years)</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Official list of all school buildings and halls across Wilgamuwa Zone that have not undergone repairs in the last 10 years or have no repair record.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Priority List</span>
                <a href="{{ route('zonal.buildings.export', ['renovation_needed' => 1]) }}" class="zems-btn-primary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export CSV
                </a>
            </div>
        </div>

        {{-- Report 2: Full Buildings Directory --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-blue-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">All Zone School Buildings</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Complete physical infrastructure inventory of all school halls, labs, dimensions, floor counts and construction years.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">All Schools</span>
                <a href="{{ route('zonal.buildings.export') }}" class="zems-btn-primary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export CSV
                </a>
            </div>
        </div>

        {{-- Report 3: Teachers Master Directory --}}
        <div class="zems-card-base p-6 flex flex-col justify-between hover:shadow-md hover:border-purple-300 transition">
            <div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 leading-snug">Teachers Service Directory</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Comprehensive teacher directory with NIC, school assignment, cadre service grade, appointment subject and retirement timeline.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">Live Filtered</span>
                <a href="{{ route('zonal.teachers') }}" class="zems-btn-secondary !py-2 !px-4 text-xs font-bold flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Go to Directory
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
