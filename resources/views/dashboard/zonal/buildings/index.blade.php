@extends('layouts.dashboard')
@section('title', 'Buildings & Physical Infrastructure')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="buildingManager()">

    {{-- Gradient Header --}}
    <div class="zems-header">
        <div class="flex items-start gap-4">
            <div class="hidden sm:flex w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center border border-white/10 shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="zems-header-title">Buildings & Physical Infrastructure</h1>
                <p class="zems-header-subtitle">Track school buildings, renovation timelines (&ge;10 yrs), multi-story facilities & sanitation data</p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <a href="{{ route('zonal.buildings.export', ['renovation_needed' => 1]) }}" class="zems-btn-header" title="Download buildings that have not been renovated in 10+ years">
                <svg class="w-4 h-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Export Needing Renovation (&ge;10 Yrs)
            </a>
            <a href="{{ route('zonal.buildings.export') }}" class="zems-btn-header">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export All CSV
            </a>
            <button type="button" @click="openCreateModal('{{ $schoolId ?? '' }}')" class="zems-btn-primary !bg-white !text-blue-700 hover:!bg-blue-50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Building
            </button>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
    </div>
    @endif

    {{-- Summary Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Buildings --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Buildings</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalBuildings }}</p>
                <span class="text-[10px] text-slate-400 font-semibold">Across Wilgamuwa Zone</span>
            </div>
        </div>

        {{-- Needing Renovation (>=10 Yrs) --}}
        <a href="{{ route('zonal.buildings', ['renovation_needed' => 1]) }}" class="zems-card-base p-6 flex items-center gap-4 hover:border-amber-400 hover:shadow-md transition group">
            <div class="w-12 h-12 rounded-xl bg-amber-50 group-hover:bg-amber-500 flex items-center justify-center shrink-0 transition">
                <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Needs Renovation</p>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-100 text-amber-800">&ge; 10 Yrs</span>
                </div>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $renovationCount }}</p>
                <span class="text-[10px] text-amber-600 font-bold group-hover:underline">Click to view urgency</span>
            </div>
        </a>

        {{-- Multi-Floor Structures --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Multi-Story Buildings</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $multiFloorCount }}</p>
                <span class="text-[10px] text-purple-600 font-semibold">2+ Floors recorded</span>
            </div>
        </div>

        {{-- Sanitation / Toilets --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Toilet Units</p>
                    <span class="text-sm font-black text-emerald-700">{{ $totalSanitationUnits }}</span>
                </div>
                <div class="flex items-center justify-between text-xs font-bold mt-1.5 text-slate-600">
                    <span>Boys: <b class="text-blue-600">{{ $totalBoysToilets }}</b></span>
                    <span>Girls: <b class="text-pink-600">{{ $totalGirlsToilets }}</b></span>
                    <span>Staff: <b class="text-slate-800">{{ $totalTeacherToilets }}</b></span>
                </div>
                <span class="text-[10px] text-slate-400 font-semibold">Total zone sanitation capacity</span>
            </div>
        </div>
    </div>

    {{-- Filter, School Selector & Search Bar --}}
    <div class="zems-card-base p-5 space-y-4">
        <form method="GET" action="{{ route('zonal.buildings') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            {{-- School Selector Dropdown --}}
            <div class="md:col-span-5">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Select School</label>
                <select name="school_id" onchange="this.form.submit()" class="zems-input h-[44px]">
                    <option value="">All Schools (Whole Zone Directory)</option>
                    <optgroup label="Wilgamuwa Division (28 Schools)">
                        @foreach($schools->where('eduDivision', 'Wilgamuwa') as $s)
                        <option value="{{ $s->id }}" {{ $schoolId == $s->id ? 'selected' : '' }}>
                            {{ $s->censusNo ? $s->censusNo . ' - ' : '' }}{{ $s->name }}
                        </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Laggala / Pallegama Division (17 Schools)">
                        @foreach($schools->where('eduDivision', 'Laggala') as $s)
                        <option value="{{ $s->id }}" {{ $schoolId == $s->id ? 'selected' : '' }}>
                            {{ $s->censusNo ? $s->censusNo . ' - ' : '' }}{{ $s->name }}
                        </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            {{-- Search Bar --}}
            <div class="md:col-span-4">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search Building / Size</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, size or school..." class="zems-input pl-10 h-[44px]">
                </div>
            </div>

            {{-- Renovation Filter & Action Buttons --}}
            <div class="md:col-span-3 flex items-end gap-2 pt-5">
                <label class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-slate-200 hover:bg-slate-100 px-3 py-2.5 rounded-xl h-[44px] flex-1 text-xs font-bold text-slate-700 select-none">
                    <input type="checkbox" name="renovation_needed" value="1" {{ $renovationOnly ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500 h-4 w-4">
                    <span>&ge; 10 Yrs Repair</span>
                </label>
                <button type="submit" class="zems-btn-secondary h-[44px] px-3.5" title="Filter results">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </button>
                @if($schoolId || $renovationOnly || $search)
                <a href="{{ route('zonal.buildings') }}" class="zems-btn-secondary h-[44px] px-3 text-rose-600 hover:bg-rose-50" title="Clear filters">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
                @endif
            </div>
        </form>

        {{-- Active School Sanitation Quick Panel (Shown when a school is selected) --}}
        @if($selectedSchool)
        <div class="p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 mt-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-black text-slate-900">{{ $selectedSchool->name }} &bull; Sanitation Units</h3>
                    <span class="text-xs font-bold bg-white border border-blue-200 text-blue-700 px-2 py-0.5 rounded-full">Census: {{ $selectedSchool->censusNo }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Collect and update toilet facilities for this school</p>
            </div>

            <form method="POST" action="{{ route('zonal.schools.sanitation.update', $selectedSchool->id) }}" class="flex items-center gap-3 flex-wrap">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-bold text-slate-600">Boys:</span>
                    <input type="number" min="0" name="boysToilets" value="{{ $selectedSchool->sanitation->boysToilets ?? 0 }}" class="w-16 h-9 rounded-lg border-slate-300 text-center font-bold text-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-bold text-slate-600">Girls:</span>
                    <input type="number" min="0" name="girlsToilets" value="{{ $selectedSchool->sanitation->girlsToilets ?? 0 }}" class="w-16 h-9 rounded-lg border-slate-300 text-center font-bold text-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-bold text-slate-600">Staff:</span>
                    <input type="number" min="0" name="teacherToilets" value="{{ $selectedSchool->sanitation->teacherToilets ?? 0 }}" class="w-16 h-9 rounded-lg border-slate-300 text-center font-bold text-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="zems-btn-primary h-9 px-4 text-xs font-bold">
                    Save Toilets
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Buildings Table --}}
    <div class="zems-table-container">
        <div class="overflow-x-auto">
            <table class="zems-table">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th">Building Name</th>
                        <th class="zems-table-th">School / Institution</th>
                        <th class="zems-table-th">Size / Dimensions</th>
                        <th class="zems-table-th">Floors</th>
                        <th class="zems-table-th">Year Built</th>
                        <th class="zems-table-th">Last Repair</th>
                        <th class="zems-table-th">Status</th>
                        <th class="zems-table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildings as $b)
                    @php
                        $age = $b->constructionYear ? (date('Y') - $b->constructionYear) : null;
                        $yearsSinceRepair = $b->lastRepairYear ? (date('Y') - $b->lastRepairYear) : null;
                        $needsRenovation = !$b->lastRepairYear || $yearsSinceRepair >= 10;
                    @endphp
                    <tr class="zems-table-row hover:bg-slate-50/80 transition">
                        {{-- Building Name --}}
                        <td class="zems-table-td">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $needsRenovation ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700' }} flex items-center justify-center font-black text-sm shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 leading-tight">{{ $b->buildingName }}</div>
                                    @if($b->notes)
                                    <div class="text-[11px] text-slate-400 font-medium truncate max-w-xs">{{ $b->notes }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- School Name --}}
                        <td class="zems-table-td">
                            <div class="font-bold text-slate-800 text-xs">{{ $b->school->name ?? 'Unknown School' }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1 mt-0.5">
                                <span class="text-blue-600">{{ $b->school->eduDivision ?? 'Wilgamuwa' }}</span>
                                @if($b->school->censusNo ?? null)
                                <span>&bull; Census: {{ $b->school->censusNo }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- Size / Dimensions --}}
                        <td class="zems-table-td">
                            <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                {{ $b->size ?: 'N/A' }}
                            </span>
                        </td>

                        {{-- Floors --}}
                        <td class="zems-table-td">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $b->floors > 1 ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $b->floors }} {{ $b->floors > 1 ? 'Floors' : 'Floor' }}
                            </span>
                        </td>

                        {{-- Year Built --}}
                        <td class="zems-table-td">
                            @if($b->constructionYear)
                            <div class="text-xs font-bold text-slate-800">{{ $b->constructionYear }}</div>
                            <div class="text-[10px] text-slate-400 font-semibold">{{ $age }} yrs ago</div>
                            @else
                            <span class="text-slate-400 text-xs italic">Unknown</span>
                            @endif
                        </td>

                        {{-- Year of Last Repair --}}
                        <td class="zems-table-td">
                            @if($b->lastRepairYear)
                                <div class="text-xs font-bold text-slate-800">{{ $b->lastRepairYear }}</div>
                                @if($needsRenovation)
                                <span class="inline-flex items-center gap-1 text-[10px] font-black text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded mt-0.5">
                                    ⚠️ {{ $yearsSinceRepair }} yrs ago (&ge;10 yrs)
                                </span>
                                @else
                                <span class="text-[10px] text-emerald-600 font-bold">{{ $yearsSinceRepair }} yrs ago</span>
                                @endif
                            @else
                                <span class="inline-flex items-center text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">
                                    No repair record
                                </span>
                            @endif
                        </td>

                        {{-- Status Pill --}}
                        <td class="zems-table-td">
                            @php
                                $statusClass = 'bg-slate-100 text-slate-800 border-slate-200';
                                if (stripos($b->status, 'Good') !== false) {
                                    $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                } elseif (stripos($b->status, 'Minor') !== false) {
                                    $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                } elseif (stripos($b->status, 'Major') !== false) {
                                    $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                } elseif (stripos($b->status, 'Danger') !== false || stripos($b->status, 'Condemn') !== false) {
                                    $statusClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                }
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusClass }}">
                                {{ $b->status ?: 'Good / Usable' }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="zems-table-td text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" @click="openEditModal({{ json_encode($b) }})" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Building">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button type="button" @click="openDeleteModal('{{ route('zonal.buildings.destroy', $b->id) }}', '{{ addslashes($b->buildingName) }}')" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Building">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-sm font-black uppercase tracking-wider text-slate-500">No building records found</p>
                                <button type="button" @click="openCreateModal('{{ $schoolId ?? '' }}')" class="text-xs font-bold text-blue-600 hover:underline">
                                    + Add New Building
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create / Edit Building Modal --}}
    <div x-show="showFormModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10" @click="showFormModal = false"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 animate-scale-in relative z-20">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-lg font-black text-slate-900" x-text="isEdit ? 'Edit Building Record' : 'Add New Building'"></h3>
                <button type="button" @click="showFormModal = false" class="text-slate-400 hover:text-slate-700 font-black text-lg">&times;</button>
            </div>

            <form :action="formAction" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- School Selection --}}
                <div>
                    <label class="zems-input-label">Assigned School <span class="text-rose-500">*</span></label>
                    <select name="schoolId" x-model="formData.schoolId" required class="zems-input">
                        <option value="">-- Choose School --</option>
                        @foreach($schools as $s)
                        <option value="{{ $s->id }}">{{ $s->censusNo ? $s->censusNo . ' - ' : '' }}{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Building Name & Size --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="zems-input-label">Building Name / Hall <span class="text-rose-500">*</span></label>
                        <input type="text" name="buildingName" x-model="formData.buildingName" required placeholder="e.g. Main Hall, Science Lab" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">Size / Dimensions</label>
                        <input type="text" name="size" x-model="formData.size" placeholder="e.g. 80*20 or 100*50" class="zems-input">
                    </div>
                </div>

                {{-- Floors, Construction Year, Last Repair Year --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="zems-input-label">Floors Count</label>
                        <input type="number" name="floors" min="1" max="20" x-model="formData.floors" class="zems-input" placeholder="1">
                    </div>
                    <div>
                        <label class="zems-input-label">Year Built</label>
                        <input type="number" name="constructionYear" min="1800" max="2050" x-model="formData.constructionYear" placeholder="e.g. 2005" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">Last Repair Year</label>
                        <input type="number" name="lastRepairYear" min="1800" max="2050" x-model="formData.lastRepairYear" placeholder="e.g. 2018" class="zems-input">
                    </div>
                </div>

                {{-- Condition Status --}}
                <div>
                    <label class="zems-input-label">Condition / Status</label>
                    <select name="status" x-model="formData.status" class="zems-input">
                        <option value="Good / Usable">Good / Usable</option>
                        <option value="Minor Repairs Needed">Minor Repairs Needed</option>
                        <option value="Needs Major Renovation">Needs Major Renovation (&ge;10 yrs)</option>
                        <option value="Dangerous / Condemned">Dangerous / Condemned</option>
                    </select>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="zems-input-label">Notes & Description</label>
                    <textarea name="notes" x-model="formData.notes" rows="2" placeholder="Specific repairs needed, funding source, or usage remarks..." class="zems-input"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showFormModal = false" class="zems-btn-secondary">Cancel</button>
                    <button type="submit" class="zems-btn-primary" x-text="isEdit ? 'Save Changes' : 'Create Building'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10" @click="showDeleteModal = false"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-scale-in text-center relative z-20">
            <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Delete Building?</h3>
            <p class="text-sm text-slate-500 mt-2">Are you sure you want to delete <span x-text="deleteBuildingName" class="font-bold text-slate-700"></span>? This action cannot be undone.</p>
            <form :action="deleteAction" method="POST" class="mt-6 flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="zems-btn-secondary flex-1">Cancel</button>
                <button type="submit" class="zems-btn-primary !bg-rose-600 hover:!bg-rose-700 flex-1">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
function buildingManager() {
    return {
        showFormModal: false,
        isEdit: false,
        formAction: '{{ route("zonal.buildings.store") }}',
        formData: {
            schoolId: '',
            buildingName: '',
            size: '',
            floors: 1,
            constructionYear: '',
            lastRepairYear: '',
            status: 'Good / Usable',
            notes: ''
        },
        showDeleteModal: false,
        deleteAction: '',
        deleteBuildingName: '',

        openCreateModal(defaultSchoolId = '') {
            this.isEdit = false;
            this.formAction = '{{ route("zonal.buildings.store") }}';
            this.formData = {
                schoolId: defaultSchoolId || '',
                buildingName: '',
                size: '',
                floors: 1,
                constructionYear: '',
                lastRepairYear: '',
                status: 'Good / Usable',
                notes: ''
            };
            this.showFormModal = true;
        },

        openEditModal(building) {
            this.isEdit = true;
            this.formAction = '{{ url("dashboard/zonal/buildings") }}/' + building.id;
            this.formData = {
                schoolId: building.schoolId,
                buildingName: building.buildingName,
                size: building.size || '',
                floors: building.floors || 1,
                constructionYear: building.constructionYear || '',
                lastRepairYear: building.lastRepairYear || '',
                status: building.status || 'Good / Usable',
                notes: building.notes || ''
            };
            this.showFormModal = true;
        },

        openDeleteModal(actionUrl, name) {
            this.deleteAction = actionUrl;
            this.deleteBuildingName = name;
            this.showDeleteModal = true;
        }
    };
}
</script>
@endsection
