@extends('layouts.dashboard')
@section('title', 'Manage Teachers')

@section('content')
<style>
    /* Filters Toggle Button Active State */
    .filter-btn-open {
        background-color: #0f172a !important;
        color: #ffffff !important;
        border-color: #0f172a !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25) !important;
    }
    .filter-btn-open:hover {
        background-color: #1e293b !important;
        border-color: #1e293b !important;
        color: #ffffff !important;
    }
    .filter-btn-open * {
        color: #ffffff !important;
    }
    .filter-btn-open svg {
        stroke: #ffffff !important;
        color: #ffffff !important;
    }
    .filter-btn-open #activeFiltersBadge {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }
</style>
<div class="space-y-6 animate-fade-in" x-data="confirmDelete">

    {{-- Gradient Header --}}
    <div class="zems-header">
        <div class="flex items-start gap-4">
            <div class="hidden sm:flex w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center border border-white/10 shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Teachers Management</h1>
                <p class="zems-header-subtitle">Manage teacher profiles and institutional service records</p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button onclick="exportAllPagesTeachers()" class="zems-btn-header" title="Export complete database records for all filtered teachers">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Export All Pages</span>
            </button>
            <a href="{{ route('zonal.teachers.create') }}" class="zems-btn-primary !bg-white !text-blue-700 hover:!bg-blue-50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Teacher
            </a>
        </div>
    </div>

    {{-- Summary Stats Cards (Interactive) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Active Pool --}}
        <div onclick="setQuickFilter('all')" class="zems-card-base p-6 flex items-center gap-4 cursor-pointer hover:shadow-md hover:border-blue-300 transition group" title="Click to view all active teachers">
            <div class="w-12 h-12 rounded-xl bg-blue-50 group-hover:bg-blue-600 flex items-center justify-center shrink-0 transition">
                <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Pool</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalTeachers }}</p>
                <span class="text-[10px] text-blue-600 font-bold group-hover:underline">Click to view all</span>
            </div>
        </div>

        {{-- Male / Female --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Staff Gender</p>
                <div class="flex items-center gap-2 mt-1">
                    <button type="button" onclick="setQuickFilter('male')" class="text-lg font-black text-slate-800 hover:text-cyan-600 transition underline-offset-4 hover:underline" title="Filter Male">
                        {{ $maleCount }} <span class="text-xs font-bold text-slate-400 uppercase">M</span>
                    </button>
                    <span class="text-slate-300 text-lg">/</span>
                    <button type="button" onclick="setQuickFilter('female')" class="text-lg font-black text-slate-800 hover:text-emerald-600 transition underline-offset-4 hover:underline" title="Filter Female">
                        {{ $femaleCount }} <span class="text-xs font-bold text-slate-400 uppercase">F</span>
                    </button>
                </div>
                <span class="text-[10px] text-slate-400 font-semibold">Click M or F to filter</span>
            </div>
        </div>

        {{-- Retiring Soon --}}
        <div onclick="setQuickFilter('retiring')" class="zems-card-base p-6 flex items-center gap-4 cursor-pointer hover:shadow-md hover:border-orange-300 transition group" title="Click to view teachers retiring within 5 years">
            <div class="w-12 h-12 rounded-xl bg-orange-50 group-hover:bg-orange-500 flex items-center justify-center shrink-0 transition">
                <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Retiring Soon</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-2xl font-black text-slate-900">{{ $retiringCount }}</p>
                    <p class="text-xs font-bold text-orange-500 bg-orange-100 px-2 py-0.5 rounded-md">Within 5 Years</p>
                </div>
                <span class="text-[10px] text-orange-600 font-bold group-hover:underline">Click to filter</span>
            </div>
        </div>

        {{-- Principals (SLPS) --}}
        <div onclick="setQuickFilter('principals')" class="zems-card-base p-6 flex items-center gap-4 cursor-pointer hover:shadow-md hover:border-purple-300 transition group" title="Click to view all principals heading schools">
            <div class="w-12 h-12 rounded-xl bg-purple-50 group-hover:bg-purple-600 flex items-center justify-center shrink-0 transition">
                <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/></svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Principals</p>
                    <span class="text-[10px] text-purple-600 font-bold group-hover:underline">Click to filter</span>
                </div>
                <div class="flex flex-col gap-0.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-500">Grade III / II / I:</span>
                        <span class="font-black text-slate-900">{{ $slpsGrade3 }} / {{ $slpsGrade2 }} / {{ $slpsGrade1 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-500">Acting Principals:</span>
                        <span class="font-black text-slate-900">{{ $actingPrincipals }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1 border-t border-slate-100 mt-1">
                        <span class="font-black text-slate-700 text-xs">Heading Schools:</span>
                        <span class="font-black text-purple-600 text-sm">{{ $totalPrincipals }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Advanced Filter Section --}}
    <div class="zems-card-base p-5 space-y-4">
        {{-- Quick Filter Chips --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-100 no-scrollbar text-xs">
            <span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider shrink-0 mr-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Quick Filters:
            </span>
            <button type="button" onclick="setQuickFilter('all')" id="chip-all" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-slate-900 bg-slate-900 text-white shrink-0 shadow-sm">
                All ({{ $totalTeachers }})
            </button>
            <button type="button" onclick="setQuickFilter('principals')" id="chip-principals" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-purple-200 bg-purple-50 text-purple-700 hover:bg-purple-100 shrink-0">
                🏛️ Principals ({{ $totalPrincipals }})
            </button>
            <button type="button" onclick="setQuickFilter('retiring')" id="chip-retiring" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-orange-200 bg-orange-50 text-orange-700 hover:bg-orange-100 shrink-0">
                ⏳ Retiring &le; 5 Yrs ({{ $retiringCount }})
            </button>
            <button type="button" onclick="setQuickFilter('wilgamuwa')" id="chip-wilgamuwa" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 shrink-0">
                Wilgamuwa Div ({{ $wilgamuwaCount }})
            </button>
            <button type="button" onclick="setQuickFilter('laggala')" id="chip-laggala" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 shrink-0">
                Laggala Div ({{ $laggalaCount }})
            </button>
            <button type="button" onclick="setQuickFilter('female')" id="chip-female" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 shrink-0">
                Female ({{ $femaleCount }})
            </button>
            <button type="button" onclick="setQuickFilter('male')" id="chip-male" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-cyan-200 bg-cyan-50 text-cyan-700 hover:bg-cyan-100 shrink-0">
                Male ({{ $maleCount }})
            </button>
            <button type="button" onclick="setQuickFilter('primary')" id="chip-primary" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 shrink-0">
                Primary Ed ({{ $primaryCount }})
            </button>
            <button type="button" onclick="setQuickFilter('service_8_plus')" id="chip-service_8_plus" class="quick-chip px-3 py-1.5 rounded-xl font-bold transition border border-teal-200 bg-teal-50 text-teal-700 hover:bg-teal-100 shrink-0" title="Click to filter teachers working at current school for 8 years or more">
                🏫 &ge; 8 Yrs at School ({{ $eightYearsAtSchoolCount ?? 0 }})
            </button>
        </div>

        {{-- Search Input & Filter Toggle Bar --}}
        <div class="flex gap-4 items-center flex-col sm:flex-row">
            <div class="relative flex-1 w-full">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="teacherSearchInput" placeholder="Search by Name, NIC, Mobile, File No, or School..."
                       class="zems-input pl-12 h-[46px] w-full">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="button" id="filtersToggleBtn" onclick="toggleFilters()" class="zems-btn-secondary h-[46px] px-4 flex-1 sm:flex-none uppercase tracking-wide text-xs relative flex items-center justify-center gap-2 font-bold transition-all duration-200" title="Toggle advanced filters">
                    <svg id="filtersIcon" class="w-4 h-4 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span id="filtersBtnText">Filters</span>
                    <span id="activeFiltersBadge" style="display:none;" class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-[10px] font-black text-white shrink-0">0</span>
                    <svg id="filtersChevron" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <button type="button" id="resetFiltersTopBtn" onclick="resetFilters()" style="display:none;" class="zems-btn-secondary h-[46px] px-3 text-rose-600 hover:text-rose-700 hover:bg-rose-50 border-rose-200 transition" title="Reset all filters">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Advanced Filter Drawer --}}
        <div id="advancedFilters" style="display:none;" class="pt-2">
            <div class="bg-slate-50/90 border border-slate-200 rounded-2xl p-5 shadow-inner space-y-4">
                {{-- Drawer Top Header Bar --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-200/80 flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">Advanced Filter Studio</span>
                            <span class="ml-2 text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-full">12 Dimensions</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="resetFilters()" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200 px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Clear All
                        </button>
                        <button type="button" onclick="toggleFilters(false)" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl transition flex items-center gap-1 shadow-sm" title="Close filter panel">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Close
                        </button>
                    </div>
                </div>

                {{-- Filter Options Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- 1. Educational Division --}}
                <div>
                    <label class="zems-input-label">Educational Division</label>
                    <select id="filterDivision" class="zems-input">
                        <option value="">All Divisions (Wilgamuwa & Laggala)</option>
                        <option value="Wilgamuwa">Wilgamuwa Division (28 Schools)</option>
                        <option value="Laggala">Laggala / Pallegama Division (17 Schools)</option>
                    </select>
                </div>

                {{-- 2. Assigned School --}}
                <div>
                    <label class="zems-input-label">Assigned School / Institution</label>
                    <select id="filterSchool" class="zems-input">
                        <option value="">All Schools (45)</option>
                        <optgroup label="Wilgamuwa Division (28 Schools)">
                            @foreach($schools->where('eduDivision', 'Wilgamuwa') as $school)
                            <option value="{{ $school->id }}" data-division="wilgamuwa">{{ $school->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Laggala / Pallegama Division (17 Schools)">
                            @foreach($schools->where('eduDivision', 'Laggala') as $school)
                            <option value="{{ $school->id }}" data-division="laggala">{{ $school->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                {{-- 3. Current Position --}}
                <div>
                    <label class="zems-input-label">Current Position / Role</label>
                    <select id="filterPosition" class="zems-input">
                        <option value="">All Positions</option>
                        <option value="all_principals">★ All Heading Schools (Principal & Acting)</option>
                        <option value="principal_only">1 - Principal</option>
                        <option value="acting_principal">2 - Acting Principal / Cover up Duties</option>
                        <option value="deputy_principal">3 - Deputy Principal</option>
                        <option value="assistant_principal">4 - Assistant Principal</option>
                        <option value="sectional_head">5 - Sectional Head</option>
                        <option value="grade_head">6 - Grade Head</option>
                        <option value="teacher">7 - Teacher</option>
                        <option value="do">8 - DO (Development Officer)</option>
                    </select>
                </div>

                {{-- 4. Service / Cadre --}}
                <div>
                    <label class="zems-input-label">Service / Cadre</label>
                    <select id="filterService" class="zems-input">
                        <option value="">All Services</option>
                        <option value="slts">3 - Sri Lanka Teacher Service (SLTS)</option>
                        <option value="slps">2 - Sri Lanka Principal Service (SLPrS)</option>
                        <option value="do">4 - Development Officer Service (DO)</option>
                        <option value="other">5 - Other / Not belongs to a Service</option>
                    </select>
                </div>

                {{-- 5. Service Grade / Class --}}
                <div>
                    <label class="zems-input-label">Grade / Class</label>
                    <select id="filterGrade" class="zems-input">
                        <option value="">All Grades & Classes</option>
                        <optgroup label="SLPS (Principal Service)">
                            <option value="slps_1">SLPS Grade I</option>
                            <option value="slps_2">SLPS Grade II</option>
                            <option value="slps_3">SLPS Grade III</option>
                        </optgroup>
                        <optgroup label="SLTS (Teacher Service Classes)">
                            <option value="class_1">Class 1</option>
                            <option value="class_2_1">Class 2-I</option>
                            <option value="class_2_2">Class 2-II</option>
                            <option value="class_3_1_a">Class 3-I (A)</option>
                            <option value="class_3_1_b">Class 3-I (B)</option>
                            <option value="class_3_1_c">Class 3-I (C)</option>
                            <option value="class_3_2">Class 3-II</option>
                        </optgroup>
                    </select>
                </div>

                {{-- 6. Retirement Timeline --}}
                <div>
                    <label class="zems-input-label">Retirement Timeline (Age 60)</label>
                    <select id="filterRetirement" class="zems-input">
                        <option value="">All Timelines</option>
                        <option value="1">⚠️ Retiring within 1 Year (&le; 1 Year)</option>
                        <option value="3">⏳ Retiring within 3 Years (&le; 3 Years)</option>
                        <option value="5">📅 Retiring within 5 Years (&le; 5 Years)</option>
                        <option value="over_5">✅ Over 5 Years Left (&gt; 5 Years)</option>
                    </select>
                </div>

                {{-- 7. Appointment Subject --}}
                <div>
                    <label class="zems-input-label">Appointment Subject / පත්වීම් විෂය</label>
                    <select id="filterAppointmentSubject" class="zems-input cursor-pointer font-medium">
                        <option value="">All Appointment Subjects ({{ count($allSubjectsList ?? []) }})</option>
                        @foreach(($allSubjectsList ?? []) as $subj)
                        <option value="{{ $subj }}">{{ $subj }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 8. First Most Teaching Subject --}}
                <div>
                    <label class="zems-input-label">First Most Teaching Subject / ප්‍රධාන ඉගැන්වීම් විෂය</label>
                    <select id="filterFirstMostTeachingSubject" class="zems-input cursor-pointer font-medium">
                        <option value="">All Teaching Subjects ({{ count($allSubjectsList ?? []) }})</option>
                        @foreach(($allSubjectsList ?? []) as $subj)
                        <option value="{{ $subj }}">{{ $subj }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 9. Service at Current School (>= 8 Years) --}}
                <div>
                    <label class="zems-input-label">Service at Current School / වත්මන් පාසලේ කාලය</label>
                    <select id="filterCurrentSchoolService" class="zems-input cursor-pointer font-semibold text-teal-800">
                        <option value="">All Service Periods</option>
                        <option value="8_plus">🏫 8 Years or More (&ge; 8 Years) / වසර 8 හෝ ඊට වැඩි</option>
                        <option value="under_8">Less than 8 Years (&lt; 8 Years) / වසර 8ට අඩු</option>
                    </select>
                </div>

                {{-- 8. Staff Gender --}}
                <div>
                    <label class="zems-input-label">Staff Gender</label>
                    <select id="filterGender" class="zems-input">
                        <option value="">All Genders</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                {{-- 9. Appointment Medium --}}
                <div>
                    <label class="zems-input-label">Appointment Medium</label>
                    <select id="filterMedium" class="zems-input">
                        <option value="">All Mediums</option>
                        <option value="sinhala">1 - Sinhala Medium</option>
                        <option value="tamil">2 - Tamil Medium</option>
                        <option value="english">3 - English Medium</option>
                    </select>
                </div>

                {{-- 10. Attachment / Release Status --}}
                <div>
                    <label class="zems-input-label">Attachment / Release Status</label>
                    <select id="filterAttachment" class="zems-input">
                        <option value="">All Staff</option>
                        <option value="attached_any">Any Attached / Released Staff</option>
                        <option value="attached_office">Attached to Educational Office (ZEO/PDC/DEO/ITDLH)</option>
                        <option value="attached_school">Attached to Another School</option>
                        <option value="not_attached">Regular / Not Attached</option>
                    </select>
                </div>
            </div>

            {{-- Filter Footer Actions --}}
            <div class="pt-3 border-t border-slate-200/70 flex items-center justify-between flex-wrap gap-2">
                <span class="text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    12 interactive filters ready &bull; instant results
                </span>
                <button type="button" onclick="resetFilters()" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1.5 bg-white hover:bg-rose-50 border border-slate-200 px-3.5 py-1.5 rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset All Filters
                </button>
            </div>
        </div>
    </div>

        {{-- Dynamic Results & Active Filter Pills Bar --}}
        <div class="pt-2 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Showing:</span>
                <span id="filteredTeacherCount" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-blue-100 text-blue-800">
                    {{ $totalTeachers }}
                </span>
                <span class="text-xs text-slate-400 font-medium">of {{ $totalTeachers }} teachers</span>
            </div>

            <div id="activeFilterPills" class="flex items-center gap-1.5 flex-wrap">
                {{-- Dynamic pills injected via JS --}}
            </div>
        </div>
    </div>

    {{-- Teachers Table --}}
    <div class="zems-table-container">
        <div class="overflow-x-auto">
            <table class="zems-table">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th">Staff Identity</th>
                        <th class="zems-table-th">NIC / Gender</th>
                        <th class="zems-table-th">School</th>
                        <th class="zems-table-th">Position</th>
                        <th class="zems-table-th">Retirement</th>
                        <th class="zems-table-th">Next Increment</th>
                        <th class="zems-table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="teacherTableBody">
                    @forelse($teachers as $teacher)
                    @php
                        // Retirement calculation
                        $retirementDate = null;
                        $yearsLeft = null;
                        if ($teacher->dateOfBirth) {
                            $bday = $teacher->dateOfBirth;
                            $retirementDate = $bday->copy()->addYears(60)->subDay();
                            $yearsLeft = max(0, (int) now()->diffInYears($retirementDate, false));
                        }

                        // Next increment calculation
                        $nextIncrement = null;
                        $incrBase = $teacher->clsappoint ?? $teacher->firstappoint;
                        if ($incrBase) {
                            $nextIncrement = $incrBase->copy()->setYear(now()->year);
                            if ($nextIncrement->lt(now())) {
                                $nextIncrement->addYear();
                            }
                        }

                        // Years at current school calculation
                        $schoolAppointDate = $teacher->schappoint;
                        $yearsAtSchool = null;
                        if ($schoolAppointDate) {
                            $sDate = ($schoolAppointDate instanceof \Carbon\Carbon) ? $schoolAppointDate : \Carbon\Carbon::parse($schoolAppointDate);
                            $yearsAtSchool = (float) number_format($sDate->diffInYears(now()), 1, '.', '');
                        }

                        $positionTitle = $teacher->position ?: 'Teacher';
                        $isPrincipal = \App\Models\Teacher::isPrincipalPosition($positionTitle);

                        $apptSubjectVal = trim($teacher->appt_code ?: '');
                        $t1SubjectVal = trim($teacher->t1_code ?: '');
                    @endphp
                    <tr class="teacher-row zems-table-row"
                        data-id="{{ $teacher->id }}"
                        data-name="{{ strtolower($teacher->fullName ?? '') }}"
                        data-nic="{{ strtolower($teacher->nic ?? '') }}"
                        data-contact="{{ strtolower(($teacher->phone ?? '') . ' ' . ($teacher->mobile ?? '')) }}"
                        data-file="{{ strtolower(($teacher->paysheet ?? '') . ' ' . ($teacher->personal_file_no ?? '')) }}"
                        data-school="{{ $teacher->schoolId }}"
                        data-school-name="{{ strtolower($teacher->school->name ?? '') }}"
                        data-division="{{ strtolower($teacher->school->eduDivision ?? '') }}"
                        data-gender="{{ strtolower($teacher->gender ?? '') }}"
                        data-position="{{ strtolower($positionTitle) }}"
                        data-service="{{ strtolower($teacher->service ?? '') }}"
                        data-grade="{{ strtolower($teacher->class_grade ?? ($teacher->class ?? '')) }}"
                        data-medium="{{ strtolower($teacher->appt_medium ?? '') }}"
                        data-appointment-subject="{{ strtolower($apptSubjectVal) }}"
                        data-teaching-subject="{{ strtolower($t1SubjectVal) }}"
                        data-years-at-school="{{ $yearsAtSchool !== null ? $yearsAtSchool : -1 }}"
                        data-years-left="{{ $yearsLeft !== null ? $yearsLeft : 999 }}"
                        data-attached="{{ strtolower($teacher->otherschool ?? 'no') }}"
                        data-attached-census="{{ strtolower($teacher->otherschool_census ?? '') }}">
                        {{-- Staff Identity --}}
                        <td class="zems-table-td">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-black text-sm shrink-0">
                                    {{ strtoupper(substr($teacher->fullName ?? 'T', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 leading-tight">{{ $teacher->fullName }}</div>
                                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $teacher->phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- NIC / Gender --}}
                        <td class="zems-table-td">
                            <div class="font-bold text-slate-700 text-xs">{{ $teacher->nic }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-wider mt-0.5 text-blue-500">{{ $teacher->gender ?? 'N/A' }}</div>
                        </td>

                        {{-- Unit (School) --}}
                        <td class="zems-table-td">
                            <div class="flex items-center gap-1.5 font-bold text-slate-600 text-xs">
                                <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate max-w-[120px]">{{ $teacher->school->name ?? 'Unknown' }}</span>
                            </div>
                            @if($yearsAtSchool !== null && $yearsAtSchool >= 8)
                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black bg-teal-50 text-teal-700 border border-teal-200" title="Working at current school for {{ (int)$yearsAtSchool }} years">
                                    &ge;8 yrs at school ({{ (int)$yearsAtSchool }}y)
                                </span>
                            </div>
                            @endif
                        </td>

                        {{-- Position --}}
                        <td class="zems-table-td">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold leading-tight {{ $isPrincipal ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-slate-100 text-slate-800 border border-slate-200' }}">
                                {{ $positionTitle }}
                            </span>
                        </td>

                        {{-- Retirement --}}
                        <td class="zems-table-td">
                            @if($retirementDate)
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $retirementDate->format('M j, Y') }}
                                </div>
                                <div class="text-[9px] font-black uppercase tracking-wider text-amber-600">
                                    {{ $yearsLeft }} yrs left
                                </div>
                            </div>
                            @else
                            <span class="text-[10px] text-slate-400 italic">N/A</span>
                            @endif
                        </td>

                        {{-- Next Increment --}}
                        <td class="zems-table-td">
                            @if($nextIncrement)
                            <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                {{ $nextIncrement->format('M j, Y') }}
                            </div>
                            @else
                            <span class="text-[10px] text-slate-400 italic">N/A</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="zems-table-td text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('zonal.teachers.show', $teacher->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors" title="View Full Profile">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('zonal.teachers.edit', $teacher->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Edit Teacher">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button @click="open('{{ route('zonal.teachers.destroy', $teacher->id) }}', '{{ addslashes($teacher->fullName ?: $teacher->nameWithInitials) }}')"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
                                        title="Delete Record">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 px-6 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <p class="text-sm font-black uppercase tracking-wider text-slate-500">No records found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- JS No Results State --}}
            <div id="teacherNoResults" style="display:none;" class="py-20 px-6 text-center text-slate-400">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="text-sm font-black uppercase tracking-wider text-slate-500">No records matched filters</p>
                    <button onclick="resetFilters()" class="text-blue-500 font-black uppercase tracking-wider text-xs mt-2 hover:text-blue-600 transition-colors">Reset Global Filter</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-scale-in text-center relative z-20">
            <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Delete Teacher?</h3>
            <p class="text-sm text-slate-500 mt-2">Are you sure you want to delete <span x-text="itemName" class="font-bold text-slate-700"></span>?</p>
            <div class="flex gap-3 mt-6">
                <button @click="close" class="zems-btn-secondary flex-1 text-slate-600">Cancel</button>
                <button @click="submit" class="zems-btn-primary !bg-red-600 hover:!bg-red-700 flex-1">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.teacherFilterState = {
        search: document.getElementById('teacherSearchInput'),
        division: document.getElementById('filterDivision'),
        school: document.getElementById('filterSchool'),
        position: document.getElementById('filterPosition'),
        service: document.getElementById('filterService'),
        grade: document.getElementById('filterGrade'),
        retirement: document.getElementById('filterRetirement'),
        appointmentSubject: document.getElementById('filterAppointmentSubject'),
        firstMostTeachingSubject: document.getElementById('filterFirstMostTeachingSubject'),
        currentSchoolService: document.getElementById('filterCurrentSchoolService'),
        gender: document.getElementById('filterGender'),
        medium: document.getElementById('filterMedium'),
        attachment: document.getElementById('filterAttachment'),
        rows: document.querySelectorAll('.teacher-row'),
        noResults: document.getElementById('teacherNoResults'),
        counter: document.getElementById('filteredTeacherCount'),
        badge: document.getElementById('activeFiltersBadge'),
        topReset: document.getElementById('resetFiltersTopBtn'),
        pillsContainer: document.getElementById('activeFilterPills')
    };

    const s = window.teacherFilterState;

    // Division change syncs with School options
    s.division.addEventListener('change', function() {
        const selectedDiv = (this.value || '').toLowerCase();
        const schoolOptions = s.school.querySelectorAll('option');
        schoolOptions.forEach(opt => {
            if (!opt.value) return; // Keep "All Schools"
            const optDiv = (opt.getAttribute('data-division') || '').toLowerCase();
            if (!selectedDiv || optDiv === selectedDiv) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        // If current school doesn't match selected division, reset school
        const curOpt = s.school.options[s.school.selectedIndex];
        if (curOpt && curOpt.value && selectedDiv) {
            const curDiv = (curOpt.getAttribute('data-division') || '').toLowerCase();
            if (curDiv !== selectedDiv) {
                s.school.value = '';
            }
        }
        filterTeachers();
    });

    s.search.addEventListener('input', filterTeachers);
    s.school.addEventListener('change', filterTeachers);
    s.position.addEventListener('change', filterTeachers);
    s.service.addEventListener('change', filterTeachers);
    s.grade.addEventListener('change', filterTeachers);
    s.retirement.addEventListener('change', filterTeachers);
    s.appointmentSubject.addEventListener('change', filterTeachers);
    s.firstMostTeachingSubject.addEventListener('change', filterTeachers);
    s.currentSchoolService.addEventListener('change', filterTeachers);
    s.gender.addEventListener('change', filterTeachers);
    s.medium.addEventListener('change', filterTeachers);
    s.attachment.addEventListener('change', filterTeachers);

    filterTeachers();
});

function filterTeachers() {
    const s = window.teacherFilterState;
    if (!s) return;

    const search = (s.search.value || '').toLowerCase().trim();
    const division = (s.division.value || '').toLowerCase().trim();
    const school = s.school.value;
    const position = s.position.value;
    const service = s.service.value;
    const grade = s.grade.value;
    const retirement = s.retirement.value;
    const apptSubject = (s.appointmentSubject.value || '').toLowerCase().trim();
    const teachingSubject = (s.firstMostTeachingSubject.value || '').toLowerCase().trim();
    const schoolService = s.currentSchoolService.value;
    const gender = s.gender.value;
    const medium = s.medium.value;
    const attachment = s.attachment.value;

    let visibleCount = 0;
    let activeFilterCount = 0;
    const activePills = [];

    if (search) activeFilterCount++;
    if (division) {
        activeFilterCount++;
        activePills.push({ label: 'Division: ' + (division === 'wilgamuwa' ? 'Wilgamuwa' : 'Laggala'), clear: () => { s.division.value = ''; filterTeachers(); } });
    }
    if (school) {
        activeFilterCount++;
        const schoolName = s.school.options[s.school.selectedIndex]?.text || 'School';
        activePills.push({ label: 'School: ' + schoolName.slice(0, 18) + '...', clear: () => { s.school.value = ''; filterTeachers(); } });
    }
    if (position) {
        activeFilterCount++;
        const posText = s.position.options[s.position.selectedIndex]?.text || 'Position';
        activePills.push({ label: 'Role: ' + posText.replace(/^★\s*|^[0-9]+\s*-\s*/, ''), clear: () => { s.position.value = ''; filterTeachers(); } });
    }
    if (service) {
        activeFilterCount++;
        activePills.push({ label: 'Service: ' + service.toUpperCase(), clear: () => { s.service.value = ''; filterTeachers(); } });
    }
    if (grade) {
        activeFilterCount++;
        const gradeText = s.grade.options[s.grade.selectedIndex]?.text || 'Grade';
        activePills.push({ label: 'Grade: ' + gradeText, clear: () => { s.grade.value = ''; filterTeachers(); } });
    }
    if (retirement) {
        activeFilterCount++;
        const retMap = { '1': 'Retiring ≤ 1 Yr', '3': 'Retiring ≤ 3 Yrs', '5': 'Retiring ≤ 5 Yrs', 'over_5': '> 5 Yrs Left' };
        activePills.push({ label: retMap[retirement] || 'Retirement', clear: () => { s.retirement.value = ''; filterTeachers(); } });
    }
    if (apptSubject) {
        activeFilterCount++;
        const apptText = s.appointmentSubject.options[s.appointmentSubject.selectedIndex]?.text || 'Appt Subject';
        activePills.push({ label: 'Appt: ' + apptText.slice(0, 24), clear: () => { s.appointmentSubject.value = ''; filterTeachers(); } });
    }
    if (teachingSubject) {
        activeFilterCount++;
        const teachText = s.firstMostTeachingSubject.options[s.firstMostTeachingSubject.selectedIndex]?.text || 'Teaching Subject';
        activePills.push({ label: 'Teaching: ' + teachText.slice(0, 24), clear: () => { s.firstMostTeachingSubject.value = ''; filterTeachers(); } });
    }
    if (schoolService) {
        activeFilterCount++;
        const srvText = schoolService === '8_plus' ? '≥ 8 Yrs at School' : '< 8 Yrs at School';
        activePills.push({ label: srvText, clear: () => { s.currentSchoolService.value = ''; filterTeachers(); } });
    }
    if (gender) {
        activeFilterCount++;
        activePills.push({ label: 'Gender: ' + gender, clear: () => { s.gender.value = ''; filterTeachers(); } });
    }
    if (medium) {
        activeFilterCount++;
        activePills.push({ label: 'Medium: ' + medium.charAt(0).toUpperCase() + medium.slice(1), clear: () => { s.medium.value = ''; filterTeachers(); } });
    }
    if (attachment) {
        activeFilterCount++;
        const attMap = { 'attached_any': 'Attached (Any)', 'attached_office': 'Office Attached', 'attached_school': 'School Attached', 'not_attached': 'Regular' };
        activePills.push({ label: 'Status: ' + (attMap[attachment] || 'Attachment'), clear: () => { s.attachment.value = ''; filterTeachers(); } });
    }

    s.rows.forEach(function(row) {
        const name = row.getAttribute('data-name') || '';
        const nic = row.getAttribute('data-nic') || '';
        const contact = row.getAttribute('data-contact') || '';
        const file = row.getAttribute('data-file') || '';
        const rowSchool = row.getAttribute('data-school') || '';
        const rowSchoolName = row.getAttribute('data-school-name') || '';
        const rowDivision = row.getAttribute('data-division') || '';
        const rowGender = row.getAttribute('data-gender') || '';
        const rowPosition = row.getAttribute('data-position') || '';
        const rowService = row.getAttribute('data-service') || '';
        const rowGrade = row.getAttribute('data-grade') || '';
        const rowMedium = row.getAttribute('data-medium') || '';
        const rowApptSubject = row.getAttribute('data-appointment-subject') || '';
        const rowTeachingSubject = row.getAttribute('data-teaching-subject') || '';
        const rowYearsAtSchool = parseFloat(row.getAttribute('data-years-at-school')) || -1;
        const rowYearsLeft = parseFloat(row.getAttribute('data-years-left')) || 999;
        const rowAttached = row.getAttribute('data-attached') || '';
        const rowAttachedCensus = row.getAttribute('data-attached-census') || '';

        // 1. Search filter
        const matchesSearch = !search || name.includes(search) || nic.includes(search) || contact.includes(search) || file.includes(search) || rowSchoolName.includes(search);

        // 2. Division filter
        const matchesDivision = !division || rowDivision === division;

        // 3. School filter
        const matchesSchool = !school || rowSchool === school;

        // 4. Position filter
        let matchesPosition = true;
        if (position) {
            if (position === 'all_principals') {
                matchesPosition = rowPosition.includes('principal') && !rowPosition.includes('deputy') && !rowPosition.includes('assistant');
            } else if (position === 'principal_only') {
                matchesPosition = rowPosition.startsWith('1 - principal') || (rowPosition.includes('principal') && !rowPosition.includes('acting') && !rowPosition.includes('deputy') && !rowPosition.includes('assistant'));
            } else if (position === 'acting_principal') {
                matchesPosition = rowPosition.includes('acting');
            } else if (position === 'deputy_principal') {
                matchesPosition = rowPosition.includes('deputy');
            } else if (position === 'assistant_principal') {
                matchesPosition = rowPosition.includes('assistant');
            } else if (position === 'sectional_head') {
                matchesPosition = rowPosition.includes('sectional');
            } else if (position === 'grade_head') {
                matchesPosition = rowPosition.includes('grade head');
            } else if (position === 'teacher') {
                matchesPosition = rowPosition.includes('teacher') && !rowPosition.includes('acting') && !rowPosition.includes('principal') && !rowPosition.includes('sectional') && !rowPosition.includes('grade head');
            } else if (position === 'do') {
                matchesPosition = rowPosition.includes('do(') || rowPosition.includes('development officer');
            }
        }

        // 5. Service filter
        let matchesService = true;
        if (service) {
            if (service === 'slts') {
                matchesService = rowService.includes('teacher') || rowService.includes('slts');
            } else if (service === 'slps') {
                matchesService = rowService.includes('principal') || rowService.includes('slprs') || rowService.includes('slps');
            } else if (service === 'do') {
                matchesService = rowService.includes('development officer');
            } else if (service === 'other') {
                matchesService = !rowService.includes('teacher') && !rowService.includes('principal') && !rowService.includes('development officer');
            }
        }

        // 6. Grade filter
        let matchesGrade = true;
        if (grade) {
            if (grade === 'slps_1') matchesGrade = rowGrade.includes('grade i') && !rowGrade.includes('grade ii') && !rowGrade.includes('grade iii');
            else if (grade === 'slps_2') matchesGrade = rowGrade.includes('grade ii') && !rowGrade.includes('grade iii');
            else if (grade === 'slps_3') matchesGrade = rowGrade.includes('grade iii');
            else if (grade === 'class_1') matchesGrade = rowGrade.includes('class 1') && !rowGrade.includes('class 2') && !rowGrade.includes('class 3');
            else if (grade === 'class_2_1') matchesGrade = rowGrade.includes('class 2-i') && !rowGrade.includes('class 2-ii');
            else if (grade === 'class_2_2') matchesGrade = rowGrade.includes('class 2-ii');
            else if (grade === 'class_3_1_a') matchesGrade = rowGrade.includes('class 3-i (a)') || rowGrade.includes('3-i (a)');
            else if (grade === 'class_3_1_b') matchesGrade = rowGrade.includes('class 3-i (b)') || rowGrade.includes('3-i (b)');
            else if (grade === 'class_3_1_c') matchesGrade = rowGrade.includes('class 3-i (c)') || rowGrade.includes('3-i (c)');
            else if (grade === 'class_3_2') matchesGrade = rowGrade.includes('class 3-ii');
        }

        // 7. Retirement filter
        let matchesRetirement = true;
        if (retirement) {
            if (retirement === '1') matchesRetirement = rowYearsLeft !== 999 && rowYearsLeft <= 1;
            else if (retirement === '3') matchesRetirement = rowYearsLeft !== 999 && rowYearsLeft <= 3;
            else if (retirement === '5') matchesRetirement = rowYearsLeft !== 999 && rowYearsLeft <= 5;
            else if (retirement === 'over_5') matchesRetirement = rowYearsLeft > 5 && rowYearsLeft !== 999;
        }

        // 8. Appointment Subject filter
        let matchesApptSubject = true;
        if (apptSubject) {
            matchesApptSubject = rowApptSubject.includes(apptSubject);
            if (!matchesApptSubject) {
                const codeMatch = apptSubject.match(/^([0-9]{3})/);
                if (codeMatch) {
                    matchesApptSubject = rowApptSubject.includes(codeMatch[1]);
                }
            }
        }

        // 9. First Most Teaching Subject filter
        let matchesTeachingSubject = true;
        if (teachingSubject) {
            matchesTeachingSubject = rowTeachingSubject.includes(teachingSubject);
            if (!matchesTeachingSubject) {
                const codeMatch = teachingSubject.match(/^([0-9]{3})/);
                if (codeMatch) {
                    matchesTeachingSubject = rowTeachingSubject.includes(codeMatch[1]);
                }
            }
        }

        // 10. Service at Current School filter (>= 8 years)
        let matchesSchoolService = true;
        if (schoolService) {
            if (schoolService === '8_plus') {
                matchesSchoolService = rowYearsAtSchool >= 8.0;
            } else if (schoolService === 'under_8') {
                matchesSchoolService = rowYearsAtSchool >= 0 && rowYearsAtSchool < 8.0;
            }
        }

        // 11. Gender filter (Strict matching)
        let matchesGender = true;
        if (gender) {
            if (gender === 'Male') {
                matchesGender = rowGender.includes('1 - male') || (rowGender.includes('male') && !rowGender.includes('female'));
            } else if (gender === 'Female') {
                matchesGender = rowGender.includes('female');
            }
        }

        // 12. Medium filter
        let matchesMedium = true;
        if (medium) {
            if (medium === 'sinhala') matchesMedium = rowMedium.includes('sinhala');
            else if (medium === 'tamil') matchesMedium = rowMedium.includes('tamil');
            else if (medium === 'english') matchesMedium = rowMedium.includes('english');
        }

        // 13. Attachment filter
        let matchesAttachment = true;
        if (attachment) {
            if (attachment === 'attached_any') matchesAttachment = rowAttached.startsWith('yes');
            else if (attachment === 'attached_office') matchesAttachment = rowAttached.startsWith('yes') && rowAttachedCensus.startsWith('office_');
            else if (attachment === 'attached_school') matchesAttachment = rowAttached.startsWith('yes') && !rowAttachedCensus.startsWith('office_');
            else if (attachment === 'not_attached') matchesAttachment = !rowAttached.startsWith('yes');
        }

        if (matchesSearch && matchesDivision && matchesSchool && matchesPosition && matchesService &&
            matchesGrade && matchesRetirement && matchesApptSubject && matchesTeachingSubject && matchesSchoolService &&
            matchesGender && matchesMedium && matchesAttachment) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update Counter & UI elements
    s.counter.textContent = visibleCount;
    s.noResults.style.display = (visibleCount === 0 && s.rows.length > 0) ? '' : 'none';

    // Badge on Filters toggle button
    if (activeFilterCount > 0) {
        s.badge.textContent = activeFilterCount;
        s.badge.style.display = 'flex';
        s.topReset.style.display = '';
    } else {
        s.badge.style.display = 'none';
        s.topReset.style.display = 'none';
    }

    // Render active pills
    s.pillsContainer.innerHTML = '';
    activePills.forEach(p => {
        const span = document.createElement('span');
        span.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200';
        span.innerHTML = `<span>${p.label}</span><button type="button" class="text-slate-400 hover:text-rose-600 font-black ml-0.5">&times;</button>`;
        span.querySelector('button').onclick = p.clear;
        s.pillsContainer.appendChild(span);
    });

    if (activePills.length > 1) {
        const clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'text-xs font-bold text-rose-600 hover:underline ml-1';
        clearBtn.textContent = 'Clear All';
        clearBtn.onclick = resetFilters;
        s.pillsContainer.appendChild(clearBtn);
    }
}

function setQuickFilter(type) {
    resetFilters(false);

    document.querySelectorAll('.quick-chip').forEach(c => {
        c.classList.remove('bg-slate-900', 'text-white', 'border-slate-900');
    });
    const chip = document.getElementById('chip-' + type);
    if (chip) {
        chip.classList.add('bg-slate-900', 'text-white', 'border-slate-900');
    }

    const s = window.teacherFilterState;
    if (!s) return;

    if (type === 'principals') {
        s.position.value = 'all_principals';
    } else if (type === 'retiring') {
        s.retirement.value = '5';
    } else if (type === 'wilgamuwa') {
        s.division.value = 'Wilgamuwa';
    } else if (type === 'laggala') {
        s.division.value = 'Laggala';
    } else if (type === 'female') {
        s.gender.value = 'Female';
    } else if (type === 'male') {
        s.gender.value = 'Male';
    } else if (type === 'primary') {
        s.firstMostTeachingSubject.value = '101 - Primary General';
    } else if (type === 'service_8_plus') {
        s.currentSchoolService.value = '8_plus';
    }

    filterTeachers();
}

function toggleFilters(forceState = null) {
    const filters = document.getElementById('advancedFilters');
    const btn = document.getElementById('filtersToggleBtn');
    const chevron = document.getElementById('filtersChevron');
    if (!filters || !btn) return;

    const isCurrentlyOpen = (filters.style.display !== 'none');
    const shouldOpen = forceState !== null ? forceState : !isCurrentlyOpen;

    if (shouldOpen) {
        filters.style.display = 'block';
        btn.classList.add('filter-btn-open');
        if (chevron) chevron.classList.add('rotate-180');
    } else {
        filters.style.display = 'none';
        btn.classList.remove('filter-btn-open');
        if (chevron) chevron.classList.remove('rotate-180');
    }
}

function resetFilters(runFilter = true) {
    const s = window.teacherFilterState;
    if (s) {
        s.search.value = '';
        s.division.value = '';
        s.school.value = '';
        s.position.value = '';
        s.service.value = '';
        s.grade.value = '';
        s.retirement.value = '';
        s.appointmentSubject.value = '';
        s.firstMostTeachingSubject.value = '';
        s.currentSchoolService.value = '';
        s.gender.value = '';
        s.medium.value = '';
        s.attachment.value = '';

        // Reset school options visibility
        s.school.querySelectorAll('option').forEach(opt => opt.style.display = '');
    }

    document.querySelectorAll('.quick-chip').forEach(c => {
        c.classList.remove('bg-slate-900', 'text-white', 'border-slate-900');
    });
    const allChip = document.getElementById('chip-all');
    if (allChip) {
        allChip.classList.add('bg-slate-900', 'text-white', 'border-slate-900');
    }

    if (runFilter) {
        filterTeachers();
    }
}

function exportAllPagesTeachers() {
    const s = window.teacherFilterState;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("zonal.teachers.export") }}';
    form.style.display = 'none';

    // CSRF Token
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    // Get matching visible rows
    const visibleRows = Array.from(document.querySelectorAll('.teacher-row')).filter(r => r.style.display !== 'none');
    const totalRowsCount = s && s.rows ? s.rows.length : document.querySelectorAll('.teacher-row').length;

    // If a subset is filtered, send specific IDs; if all visible, export all
    if (visibleRows.length < totalRowsCount) {
        const ids = visibleRows.map(r => r.getAttribute('data-id')).filter(Boolean);
        const idsInput = document.createElement('input');
        idsInput.type = 'hidden';
        idsInput.name = 'ids';
        idsInput.value = ids.join(',');
        form.appendChild(idsInput);
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function exportTeachers() {
    exportAllPagesTeachers();
}
</script>
@endsection
