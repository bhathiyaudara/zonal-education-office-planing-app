@extends('layouts.dashboard')
@section('title', 'Student Overview & Subject Statistics')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="studentOverviewApp()">

    {{-- Header --}}
    <div class="zems-header flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm shrink-0 shadow-inner">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7l9-5-9-5-9 5 9 5z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="zems-header-title text-xl md:text-2xl font-black tracking-tight">Student & Subject Analytics</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-white/20 text-white border border-white/20">{{ $academicYear }} Census</span>
                </div>
                <p class="zems-header-subtitle text-xs md:text-sm">Comprehensive enrollment breakdown, O/L & A/L subjects matrix, streams & pirivena data</p>
            </div>
        </div>

        {{-- Export Dropdown --}}
        <div class="relative self-start md:self-auto" @click.away="exportOpen = false">
            <button type="button" @click="exportOpen = !exportOpen" class="zems-btn-secondary !bg-white/10 !text-white !border-white/20 hover:!bg-white/20 backdrop-blur-sm px-4 py-2.5 flex items-center gap-2 rounded-xl text-xs font-bold transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Export Datasets</span>
                <svg class="w-3.5 h-3.5 opacity-70 transition-transform" :class="exportOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="exportOpen" x-transition.origin.top.right class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50 text-slate-800" style="display: none;">
                <div class="px-4 py-2 border-b border-slate-100">
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Download Official Census CSVs</p>
                </div>
                <a href="{{ route('zonal.schools.students.export', ['type' => 'grades', 'academic_year' => $academicYear]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">📊</div>
                    <div>
                        <p class="font-bold">Grades 1-13 Enrollment</p>
                        <p class="text-[10px] text-slate-400 font-normal">All 45 schools complete grade breakdown</p>
                    </div>
                </a>
                <a href="{{ route('zonal.schools.students.export', ['type' => 'ol_subjects', 'academic_year' => $academicYear]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">📚</div>
                    <div>
                        <p class="font-bold">O/L Subjects Matrix</p>
                        <p class="text-[10px] text-slate-400 font-normal">17 Basket subjects across 17 secondary schools</p>
                    </div>
                </a>
                <a href="{{ route('zonal.schools.students.export', ['type' => 'al_subjects', 'academic_year' => $academicYear]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-purple-600 transition">
                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">🎓</div>
                    <div>
                        <p class="font-bold">A/L Subjects Matrix</p>
                        <p class="text-[10px] text-slate-400 font-normal">30+ A/L subjects across 8 collegiate schools</p>
                    </div>
                </a>
                <a href="{{ route('zonal.schools.students.export', ['type' => 'al_streams', 'academic_year' => $academicYear]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-amber-600 transition">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">⚖️</div>
                    <div>
                        <p class="font-bold">A/L Streams Matrix</p>
                        <p class="text-[10px] text-slate-400 font-normal">Arts, Commerce, Science, Maths, Technology</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Top Zonal KPI Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
        {{-- Total Enrolled --}}
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-4 text-white shadow-lg relative overflow-hidden flex flex-col justify-between">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/10 rounded-full blur-xl translate-x-1/2 -translate-y-1/2"></div>
            <div>
                <p class="text-indigo-100 text-[10px] font-black uppercase tracking-wider">Total Enrollment</p>
                <h2 class="text-xl md:text-2xl font-black mt-1">{{ number_format($kpis['totalStudents'] ?? $totalStudents) }}</h2>
            </div>
            <p class="text-[11px] text-indigo-100/90 font-medium mt-2">{{ $kpis['totalSchools'] ?? $totalSchools }} Schools</p>
        </div>

        {{-- Gender Breakdown --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Gender Split</p>
                <div class="flex items-baseline justify-between mt-1">
                    <span class="text-sm font-black text-blue-600">{{ number_format($kpis['totalMale'] ?? $totalMale) }} <span class="text-[10px] font-bold text-slate-400">M</span></span>
                    <span class="text-sm font-black text-pink-600">{{ number_format($kpis['totalFemale'] ?? $totalFemale) }} <span class="text-[10px] font-bold text-slate-400">F</span></span>
                </div>
            </div>
            @php
                $totM = $kpis['totalMale'] ?? $totalMale;
                $totAll = ($kpis['totalStudents'] ?? $totalStudents) ?: 1;
                $pctM = max(0, min(100, round(($totM / $totAll) * 100)));
            @endphp
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden flex mt-2">
                <div class="bg-blue-500 h-full" style="width: {{ $pctM }}%"></div>
                <div class="bg-pink-500 h-full" style="width: {{ 100 - $pctM }}%"></div>
            </div>
        </div>

        {{-- Primary (1-5) --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-black uppercase text-emerald-600 tracking-wider">Primary (1 - 5)</p>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 mt-1">{{ number_format($kpis['primaryStudents'] ?? 4153) }}</h2>
            </div>
            <p class="text-[11px] text-slate-500 font-medium mt-2">230 Classes</p>
        </div>

        {{-- Secondary (6-11) --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-black uppercase text-cyan-600 tracking-wider">Secondary (6 - 11)</p>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 mt-1">{{ number_format($kpis['secondaryStudents'] ?? 4768) }}</h2>
            </div>
            <p class="text-[11px] text-slate-500 font-medium mt-2">168 Classes</p>
        </div>

        {{-- Collegiate A/L (12-13) --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-black uppercase text-purple-600 tracking-wider">Collegiate (12 - 13)</p>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 mt-1">{{ number_format($kpis['collegiateStudents'] ?? 759) }}</h2>
            </div>
            <p class="text-[11px] text-slate-500 font-medium mt-2">29 Classes (8 Schools)</p>
        </div>

        {{-- Pirivenas & Special Ed --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-black uppercase text-amber-600 tracking-wider">Pirivena & Spec. Ed</p>
                <div class="flex items-baseline justify-between mt-1">
                    <span class="text-lg md:text-xl font-black text-amber-600">{{ number_format($kpis['pirivenaTotal'] ?? 557) }}</span>
                    <span class="text-xs font-bold text-slate-500">+{{ $kpis['specialEdStudents'] ?? 20 }} Sp.</span>
                </div>
            </div>
            <p class="text-[11px] text-slate-500 font-medium mt-2">5 Pirivenas</p>
        </div>
    </div>

    {{-- Tabs Navigation Bar --}}
    <div class="border-b border-slate-200 bg-white rounded-2xl p-1.5 shadow-sm flex flex-nowrap md:flex-wrap overflow-x-auto gap-1.5">
        <button type="button" @click="activeTab = 'enrollment'" :class="activeTab === 'enrollment' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 md:px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>School & Grade Enrollment</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'enrollment' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">45</span>
        </button>

        <button type="button" @click="activeTab = 'ol'" :class="activeTab === 'ol' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 md:px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>O/L Subjects (Grades 10 & 11)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'ol' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">17 Basket</span>
        </button>

        <button type="button" @click="activeTab = 'al'" :class="activeTab === 'al' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 md:px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>A/L Subjects & Streams</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'al' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">8 Schools</span>
        </button>

        <button type="button" @click="activeTab = 'pirivena'" :class="activeTab === 'pirivena' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 md:px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
            <span>Pirivena Institutions</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'pirivena' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">5</span>
        </button>
    </div>

    {{-- ========================================== --}}
    {{-- TAB 1: SCHOOL & GRADE ENROLLMENT --}}
    {{-- ========================================== --}}
    <div x-show="activeTab === 'enrollment'" class="space-y-4">
        {{-- Filter & Control Bar --}}
        <div class="zems-card-base p-4 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" placeholder="Search school name or census no..." class="zems-input pl-9 h-10 text-xs w-full">
                </div>
                <select x-model="division" class="zems-input h-10 text-xs w-full sm:w-40">
                    <option value="All">All Divisions</option>
                    <option value="Wilgamuwa">Wilgamuwa ({{ number_format($wilgamuwaStudents) }})</option>
                    <option value="Laggala">Laggala ({{ number_format($laggalaStudents) }})</option>
                </select>
                <select x-model="level" class="zems-input h-10 text-xs w-full sm:w-44">
                    <option value="All">All School Types</option>
                    <option value="6_13">Collegiate (6-13)</option>
                    <option value="1_13">Maha Vidyalaya (1-13)</option>
                    <option value="1_11">Junior School (1-11)</option>
                    <option value="1_5">Primary School (1-5)</option>
                </select>
            </div>

            {{-- View Mode Toggle --}}
            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl self-start md:self-auto shrink-0">
                <button type="button" @click="viewMode = 'summary'" :class="viewMode === 'summary' ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                    <span>Summary View</span>
                </button>
                <button type="button" @click="viewMode = 'detailed'" :class="viewMode === 'detailed' ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                    <span>Detailed Grades 1 - 13</span>
                </button>
            </div>
        </div>

        {{-- Schools Table --}}
        <div class="zems-table-container">
            <div class="overflow-x-auto w-full">
                <table class="zems-table" :class="viewMode === 'detailed' ? 'min-w-[1200px]' : 'min-w-[700px]'" :style="viewMode === 'detailed' ? 'min-width: 1200px;' : 'min-width: 700px;'">
                    <thead>
                        <tr class="zems-table-header-row text-[11px]">
                            <th class="zems-table-th w-10 text-center">#</th>
                            <th class="zems-table-th min-w-[220px]">School Details</th>
                            <th class="zems-table-th text-center">Type / Cat</th>

                            {{-- Summary Mode Columns --}}
                            <th x-show="viewMode === 'summary'" class="zems-table-th text-center">Primary (1-5)</th>
                            <th x-show="viewMode === 'summary'" class="zems-table-th text-center">Secondary (6-11)</th>
                            <th x-show="viewMode === 'summary'" class="zems-table-th text-center">Collegiate (12-13)</th>
                            <th x-show="viewMode === 'summary'" class="zems-table-th text-center">Spec. Ed</th>

                            {{-- Detailed Mode Columns (Grades 1-13) --}}
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-emerald-50/50">Gr 1</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-emerald-50/50">Gr 2</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-emerald-50/50">Gr 3</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-emerald-50/50">Gr 4</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-emerald-50/50">Gr 5</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-cyan-50/50">Gr 6</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-cyan-50/50">Gr 7</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-cyan-50/50">Gr 8</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-cyan-50/50">Gr 9</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-cyan-50/50">Gr 10</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-cyan-50/50">Gr 11</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-purple-50/50">Gr 12</th>
                            <th x-show="viewMode === 'detailed'" class="zems-table-th text-center bg-purple-50/50">Gr 13</th>

                            <th class="zems-table-th text-center !text-blue-600">Male</th>
                            <th class="zems-table-th text-center !text-pink-600">Female</th>
                            <th class="zems-table-th text-center">Classes</th>
                            <th class="zems-table-th text-center !text-slate-900 font-black">Total</th>
                            <th class="zems-table-th text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolsData as $index => $school)
                        @php
                            $cat = $school['category'] ?? '';
                            $grs = $school['grades'] ?? [];
                        @endphp
                        <tr class="zems-table-row transition-colors"
                            x-show="matchesSchool(@js($school['name']), @js($school['censusNo']), @js($school['eduDivision']), @js($cat))">
                            <td class="zems-table-td text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="zems-table-td">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('zonal.schools.show', $school['id']) }}" class="font-bold text-slate-900 hover:text-blue-600 leading-tight">
                                            {{ $school['name'] }}
                                        </a>
                                    </div>
                                    @if(!empty($school['name_si']))
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $school['name_si'] }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($school['censusNo'])
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">#{{ $school['censusNo'] }}</span>
                                        @endif
                                        <span class="text-[10px] font-semibold text-slate-400">{{ $school['eduDivision'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="zems-table-td text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ str_contains($cat, '6_13') ? 'bg-purple-100 text-purple-700' : (str_contains($cat, '1_13') ? 'bg-indigo-100 text-indigo-700' : (str_contains($cat, '1_11') ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700')) }}">
                                    {{ $cat ?: $school['schoolType'] }}
                                </span>
                            </td>

                            {{-- Summary Mode Data --}}
                            <td x-show="viewMode === 'summary'" class="zems-table-td text-center font-bold text-slate-700">{{ $school['primary_total'] ?: '-' }}</td>
                            <td x-show="viewMode === 'summary'" class="zems-table-td text-center font-bold text-slate-700">{{ $school['secondary_total'] ?: '-' }}</td>
                            <td x-show="viewMode === 'summary'" class="zems-table-td text-center font-bold text-slate-700">{{ $school['collegiate_total'] ?: '-' }}</td>
                            <td x-show="viewMode === 'summary'" class="zems-table-td text-center font-bold text-amber-600">{{ $school['special_ed'] ?: '-' }}</td>

                            {{-- Detailed Mode Data (Grades 1 to 13) --}}
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-emerald-50/20">{{ $grs['Grade 1']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-emerald-50/20">{{ $grs['Grade 2']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-emerald-50/20">{{ $grs['Grade 3']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-emerald-50/20">{{ $grs['Grade 4']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-emerald-50/20">{{ $grs['Grade 5']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-cyan-50/20">{{ $grs['Grade 6']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-cyan-50/20">{{ $grs['Grade 7']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-cyan-50/20">{{ $grs['Grade 8']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-cyan-50/20">{{ $grs['Grade 9']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-cyan-50/20">{{ $grs['Grade 10']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-cyan-50/20">{{ $grs['Grade 11']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-purple-50/20">{{ $grs['Grade 12']['total'] ?? '-' }}</td>
                            <td x-show="viewMode === 'detailed'" class="zems-table-td text-center text-xs font-semibold bg-purple-50/20">{{ $grs['Grade 13']['total'] ?? '-' }}</td>

                            <td class="zems-table-td text-center font-bold text-blue-600">{{ number_format($school['totalMale']) }}</td>
                            <td class="zems-table-td text-center font-bold text-pink-600">{{ number_format($school['totalFemale']) }}</td>
                            <td class="zems-table-td text-center font-semibold text-slate-600">{{ $school['totalClasses'] ?: '-' }}</td>
                            <td class="zems-table-td text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-slate-900 text-white">
                                    {{ number_format($school['totalStudents']) }}
                                </span>
                            </td>
                            <td class="zems-table-td text-right">
                                <a href="{{ route('zonal.schools.show', $school['id']) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                    <span>Profile</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="p-8 text-center text-slate-400">No schools found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TAB 2: O/L SUBJECTS BREAKDOWN (GRADES 10 & 11) --}}
    {{-- ========================================== --}}
    <div x-show="activeTab === 'ol'" class="space-y-4" style="display: none;">
        {{-- Top Highlights Bar --}}
        <div class="zems-card-base p-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span>🎯 O/L Optional Basket Subjects (Grades 10 & 11)</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Distribution of students across 17 optional subjects across 17 secondary schools</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <input type="text" x-model="olSearch" placeholder="Search subject..." class="zems-input h-9 text-xs w-full sm:w-56">
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                        <button type="button" @click="olMode = 'total'" :class="olMode === 'total' ? 'bg-white text-slate-900 shadow' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs font-bold transition">Combined</button>
                        <button type="button" @click="olMode = 'g10'" :class="olMode === 'g10' ? 'bg-white text-slate-900 shadow' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs font-bold transition">Grade 10</button>
                        <button type="button" @click="olMode = 'g11'" :class="olMode === 'g11' ? 'bg-white text-slate-900 shadow' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs font-bold transition">Grade 11</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- O/L Matrix Table --}}
        <div class="zems-table-container">
            <div class="overflow-x-auto w-full">
                <table class="zems-table min-w-[1200px] text-xs" style="min-width: 1200px;">
                    <thead>
                        <tr class="zems-table-header-row text-[11px]">
                            <th class="zems-table-th sticky left-0 bg-slate-50 z-20 min-w-[200px] shadow-sm">Subject</th>
                            <th class="zems-table-th text-center bg-indigo-50 !text-indigo-800 font-black min-w-[90px]">Zone Total</th>
                            <th class="zems-table-th text-center !text-blue-600 min-w-[60px]">Male</th>
                            <th class="zems-table-th text-center !text-pink-600 min-w-[60px]">Female</th>

                            {{-- Secondary Schools Columns --}}
                            @php
                                $sampleRow = $olSubjects[0] ?? [];
                                $olSchoolNames = array_keys($sampleRow['schools'] ?? []);
                            @endphp
                            @foreach($olSchoolNames as $sName)
                            @php
                                $shortName = trim(str_replace(['CP/WIL/', 'MAHA VIDYALAYA', 'KANISHTA VIDYALAYA', 'CENTRAL COLLEGE', 'KANISTA VIDIYALAYA', 'KANISTHTA SCHOOL', 'JUNIOR SCHOOL', 'K.V.'], '', $sName));
                            @endphp
                            <th class="zems-table-th text-center min-w-[90px] font-bold text-slate-600" title="{{ $sName }}">
                                {{ $shortName }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($olSubjects as $row)
                        @php
                            $subjSi = $row['subject'];
                            $subjEn = $row['subject_en'] ?? $subjSi;
                            $tot = $row['total'] ?? [];
                            $g10T = $tot['grade_10']['total'] ?? 0;
                            $g11T = $tot['grade_11']['total'] ?? 0;
                            $allT = $tot['all']['total'] ?? ($g10T + $g11T);
                            $allM = $tot['all']['male'] ?? 0;
                            $allF = $tot['all']['female'] ?? 0;
                            $isTotalRow = ($subjSi === 'Total');
                        @endphp
                        <tr class="{{ $isTotalRow ? 'bg-slate-100/80 font-black' : 'hover:bg-slate-50/80' }} transition-colors"
                            x-show="matchesSubject(olSearch, @js($subjSi), @js($subjEn))">
                            <td class="zems-table-td sticky left-0 {{ $isTotalRow ? 'bg-slate-100' : 'bg-white' }} z-10 font-bold shadow-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-900">{{ $subjSi }}</span>
                                    @if(!$isTotalRow && $subjEn !== $subjSi)
                                    <span class="text-[10px] text-slate-500 font-normal">({{ $subjEn }})</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Zonal Total --}}
                            <td class="zems-table-td text-center font-black bg-indigo-50/50 text-indigo-700">
                                <span x-show="olMode === 'total'">{{ number_format($allT) }}</span>
                                <span x-show="olMode === 'g10'">{{ number_format($g10T) }}</span>
                                <span x-show="olMode === 'g11'">{{ number_format($g11T) }}</span>
                            </td>
                            <td class="zems-table-td text-center font-bold text-blue-600">
                                <span x-show="olMode === 'total'">{{ number_format($allM) }}</span>
                                <span x-show="olMode === 'g10'">{{ number_format($tot['grade_10']['male'] ?? 0) }}</span>
                                <span x-show="olMode === 'g11'">{{ number_format($tot['grade_11']['male'] ?? 0) }}</span>
                            </td>
                            <td class="zems-table-td text-center font-bold text-pink-600">
                                <span x-show="olMode === 'total'">{{ number_format($allF) }}</span>
                                <span x-show="olMode === 'g10'">{{ number_format($tot['grade_10']['female'] ?? 0) }}</span>
                                <span x-show="olMode === 'g11'">{{ number_format($tot['grade_11']['female'] ?? 0) }}</span>
                            </td>

                            {{-- Per School Counts --}}
                            @foreach($olSchoolNames as $sName)
                            @php
                                $sD = $row['schools'][$sName] ?? [];
                                $sG10 = $sD['grade_10']['total'] ?? 0;
                                $sG11 = $sD['grade_11']['total'] ?? 0;
                                $sAll = $sD['all']['total'] ?? ($sG10 + $sG11);
                            @endphp
                            <td class="zems-table-td text-center">
                                <span x-show="olMode === 'total'" class="{{ $sAll > 0 ? 'font-bold text-slate-800' : 'text-slate-300' }}">
                                    {{ $sAll ?: '-' }}
                                </span>
                                <span x-show="olMode === 'g10'" class="{{ $sG10 > 0 ? 'font-bold text-slate-800' : 'text-slate-300' }}">
                                    {{ $sG10 ?: '-' }}
                                </span>
                                <span x-show="olMode === 'g11'" class="{{ $sG11 > 0 ? 'font-bold text-slate-800' : 'text-slate-300' }}">
                                    {{ $sG11 ?: '-' }}
                                </span>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TAB 3: A/L SUBJECTS & STREAMS (GRADES 12 & 13) --}}
    {{-- ========================================== --}}
    <div x-show="activeTab === 'al'" class="space-y-6" style="display: none;">
        {{-- Stream Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="zems-card-base p-5 border-l-4 border-l-purple-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase text-purple-600">Arts Stream (කලා)</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700">68.6%</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-2">521 Students</h3>
                <p class="text-xs text-slate-500 mt-1">Gr 12: 275 (88 M / 187 F) • Gr 13: 246 (93 M / 153 F)</p>
            </div>

            <div class="zems-card-base p-5 border-l-4 border-l-blue-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase text-blue-600">Commerce (වාණිජ)</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">17.1%</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-2">130 Students</h3>
                <p class="text-xs text-slate-500 mt-1">Gr 12: 84 (38 M / 46 F) • Gr 13: 46 (24 M / 22 F)</p>
            </div>

            <div class="zems-card-base p-5 border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase text-emerald-600">Science & Maths (විද්‍යා)</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">4.5%</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-2">34 Students</h3>
                <p class="text-xs text-slate-500 mt-1">Gr 12: 21 (10 M / 11 F) • Gr 13: 13 (5 M / 8 F)</p>
            </div>

            <div class="zems-card-base p-5 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase text-amber-600">Technology (තාක්ෂණවේදය)</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700">SFT/ET/BST</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-2">106+ Students</h3>
                <p class="text-xs text-slate-500 mt-1">Offered at Naminioya CC & Hathota Amuna MV</p>
            </div>
        </div>

        {{-- A/L Streams Breakdown Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">A/L Streams Distribution (විෂයය ධාරාව)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Stream enrollments across the 8 collegiate schools in Wilgamuwa Zone</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="zems-table min-w-[900px] text-xs" style="min-width: 900px;">
                    <thead>
                        <tr class="zems-table-header-row text-[11px]">
                            <th class="zems-table-th sticky left-0 bg-slate-50 z-20 min-w-[150px] shadow-sm">Stream</th>
                            <th class="zems-table-th text-center bg-indigo-50 text-indigo-800">Zone Total</th>
                            <th class="zems-table-th text-center !text-blue-600">Male</th>
                            <th class="zems-table-th text-center !text-pink-600">Female</th>
                            <th class="zems-table-th text-center">Gr 12</th>
                            <th class="zems-table-th text-center">Gr 13</th>
                            @php
                                $sampleStream = $alStreams[0] ?? [];
                                $streamSchoolNames = array_keys($sampleStream['schools'] ?? []);
                            @endphp
                            @foreach($streamSchoolNames as $sn)
                            @php
                                $short = trim(str_replace(['CP/WIL/', 'MAHA VIDYALAYA', 'CENTRAL COLLEGE'], '', $sn));
                            @endphp
                            <th class="zems-table-th text-center" title="{{ $sn }}">{{ $short }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($alStreams as $st)
                        @php
                            $stName = $st['stream'];
                            $stEn = $st['stream_en'] ?? $stName;
                            $stTot = $st['total'] ?? [];
                            $isTot = ($stName === 'Total');
                        @endphp
                        <tr class="{{ $isTot ? 'bg-slate-100 font-black' : 'hover:bg-slate-50' }}">
                            <td class="zems-table-td sticky left-0 {{ $isTot ? 'bg-slate-100' : 'bg-white' }} z-10 font-bold text-slate-900 shadow-sm">{{ $stEn }}</td>
                            <td class="zems-table-td text-center font-black bg-indigo-50/50 text-indigo-700">
                                {{ number_format($stTot['all']['total'] ?? 0) }}
                            </td>
                            <td class="zems-table-td text-center font-bold text-blue-600">{{ $stTot['all']['male'] ?? 0 }}</td>
                            <td class="zems-table-td text-center font-bold text-pink-600">{{ $stTot['all']['female'] ?? 0 }}</td>
                            <td class="zems-table-td text-center font-semibold text-slate-700">{{ $stTot['grade_12']['total'] ?? 0 }}</td>
                            <td class="zems-table-td text-center font-semibold text-slate-700">{{ $stTot['grade_13']['total'] ?? 0 }}</td>

                            @foreach($streamSchoolNames as $sn)
                            @php
                                $sVal = $st['schools'][$sn]['all']['total'] ?? 0;
                            @endphp
                            <td class="zems-table-td text-center {{ $sVal > 0 ? 'font-bold text-slate-800' : 'text-slate-300' }}">
                                {{ $sVal ?: '-' }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- A/L Subjects Matrix --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">A/L Subject-wise Student Counts (Grades 12 & 13)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Subject enrollment matrix across collegiate schools</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <input type="text" x-model="alSearch" placeholder="Search A/L subject..." class="zems-input h-9 text-xs w-full sm:w-56">
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                        <button type="button" @click="alMode = 'total'" :class="alMode === 'total' ? 'bg-white text-slate-900 shadow' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs font-bold transition">Combined</button>
                        <button type="button" @click="alMode = 'g12'" :class="alMode === 'g12' ? 'bg-white text-slate-900 shadow' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs font-bold transition">Grade 12</button>
                        <button type="button" @click="alMode = 'g13'" :class="alMode === 'g13' ? 'bg-white text-slate-900 shadow' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs font-bold transition">Grade 13</button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto w-full max-h-[600px]" style="max-height: 600px;">
                <table class="zems-table min-w-[1200px] text-xs" style="min-width: 1200px;">
                    <thead class="sticky top-0 bg-slate-100 z-20 shadow-sm">
                        <tr class="zems-table-header-row text-[11px]">
                            <th class="zems-table-th sticky left-0 bg-slate-100 z-30 min-w-[200px] shadow-sm">Subject</th>
                            <th class="zems-table-th text-center bg-indigo-50 !text-indigo-800 font-black min-w-[90px]">Zone Total</th>
                            <th class="zems-table-th text-center !text-blue-600 min-w-[60px]">Male</th>
                            <th class="zems-table-th text-center !text-pink-600 min-w-[60px]">Female</th>
                            @php
                                $sampleAl = $alSubjects[0] ?? [];
                                $alSchoolNames = array_keys($sampleAl['schools'] ?? []);
                            @endphp
                            @foreach($alSchoolNames as $sn)
                            @php
                                $short = trim(str_replace(['CP/WIL/', 'MAHA VIDYALAYA', 'CENTRAL COLLEGE'], '', $sn));
                            @endphp
                            <th class="zems-table-th text-center min-w-[85px]" title="{{ $sn }}">{{ $short }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($alSubjects as $row)
                        @php
                            $subjSi = $row['subject'];
                            $subjEn = $row['subject_en'] ?? $subjSi;
                            $tot = $row['total'] ?? [];
                            $g12T = $tot['grade_12']['total'] ?? 0;
                            $g13T = $tot['grade_13']['total'] ?? 0;
                            $allT = $tot['all']['total'] ?? ($g12T + $g13T);
                            $allM = $tot['all']['male'] ?? 0;
                            $allF = $tot['all']['female'] ?? 0;
                            $isTot = ($subjSi === 'Total');
                        @endphp
                        <tr class="{{ $isTot ? 'bg-slate-100 font-black sticky bottom-0 z-20' : 'hover:bg-slate-50' }}"
                            x-show="matchesSubject(alSearch, @js($subjSi), @js($subjEn))">
                            <td class="zems-table-td sticky left-0 {{ $isTot ? 'bg-slate-100' : 'bg-white' }} z-10 font-bold shadow-sm">
                                <div>
                                    <span class="text-slate-900">{{ $subjSi }}</span>
                                    @if(!$isTot && $subjEn !== $subjSi)
                                    <span class="text-[10px] text-slate-500 font-normal">({{ $subjEn }})</span>
                                    @endif
                                </div>
                            </td>

                            <td class="zems-table-td text-center font-black bg-indigo-50/50 text-indigo-700">
                                <span x-show="alMode === 'total'">{{ number_format($allT) }}</span>
                                <span x-show="alMode === 'g12'">{{ number_format($g12T) }}</span>
                                <span x-show="alMode === 'g13'">{{ number_format($g13T) }}</span>
                            </td>
                            <td class="zems-table-td text-center font-bold text-blue-600">
                                <span x-show="alMode === 'total'">{{ number_format($allM) }}</span>
                                <span x-show="alMode === 'g12'">{{ number_format($tot['grade_12']['male'] ?? 0) }}</span>
                                <span x-show="alMode === 'g13'">{{ number_format($tot['grade_13']['male'] ?? 0) }}</span>
                            </td>
                            <td class="zems-table-td text-center font-bold text-pink-600">
                                <span x-show="alMode === 'total'">{{ number_format($allF) }}</span>
                                <span x-show="alMode === 'g12'">{{ number_format($tot['grade_12']['female'] ?? 0) }}</span>
                                <span x-show="alMode === 'g13'">{{ number_format($tot['grade_13']['female'] ?? 0) }}</span>
                            </td>

                            @foreach($alSchoolNames as $sn)
                            @php
                                $sD = $row['schools'][$sn] ?? [];
                                $sG12 = $sD['grade_12']['total'] ?? 0;
                                $sG13 = $sD['grade_13']['total'] ?? 0;
                                $sAll = $sD['all']['total'] ?? ($sG12 + $sG13);
                            @endphp
                            <td class="zems-table-td text-center">
                                <span x-show="alMode === 'total'" class="{{ $sAll > 0 ? 'font-bold text-slate-800' : 'text-slate-300' }}">
                                    {{ $sAll ?: '-' }}
                                </span>
                                <span x-show="alMode === 'g12'" class="{{ $sG12 > 0 ? 'font-bold text-slate-800' : 'text-slate-300' }}">
                                    {{ $sG12 ?: '-' }}
                                </span>
                                <span x-show="alMode === 'g13'" class="{{ $sG13 > 0 ? 'font-bold text-slate-800' : 'text-slate-300' }}">
                                    {{ $sG13 ?: '-' }}
                                </span>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TAB 4: PIRIVENA INSTITUTIONS --}}
    {{-- ========================================== --}}
    <div x-show="activeTab === 'pirivena'" class="space-y-6" style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                <p class="text-amber-100 text-xs font-black uppercase tracking-wider mb-1">Total Pirivena Students</p>
                <h2 class="text-4xl font-black mb-2">{{ number_format($kpis['pirivenaTotal'] ?? 557) }}</h2>
                <p class="text-xs text-amber-100 font-medium">5 Recognized Pirivenas in Wilgamuwa Zone</p>
            </div>
            <div class="zems-card-base p-6 flex flex-col justify-center">
                <p class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2">Total Pirivena Teachers</p>
                <h3 class="text-3xl font-black text-slate-900">{{ number_format($kpis['pirivenaTeachers'] ?? 40) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Serving across all Pirivena grades</p>
            </div>
            <div class="zems-card-base p-6 flex flex-col justify-center">
                <p class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2">Largest Pirivena Institution</p>
                <h3 class="text-lg font-black text-slate-900">Sri Gnanissara Vidyayathanaya</h3>
                <p class="text-xs text-slate-500 mt-1">Handungamuwa (253 Students • 18 Teachers)</p>
            </div>
        </div>

        <div class="zems-table-container">
            <div class="overflow-x-auto w-full">
                <table class="zems-table min-w-[700px]" style="min-width: 700px;">
                    <thead>
                        <tr class="zems-table-header-row">
                            <th class="zems-table-th w-12 text-center">#</th>
                            <th class="zems-table-th">Pirivena Code & Name</th>
                            <th class="zems-table-th text-center">Teachers</th>
                            <th class="zems-table-th text-center">Lay Students (ගිහි)</th>
                            <th class="zems-table-th text-center">Monk Students (පැවිදි)</th>
                            <th class="zems-table-th text-center font-black">Total Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pirivenas as $i => $pv)
                        <tr class="zems-table-row">
                            <td class="zems-table-td text-center text-xs font-bold text-slate-400">{{ $i + 1 }}</td>
                            <td class="zems-table-td font-bold text-slate-900">
                                <div>
                                    <span class="text-sm">{{ $pv['name'] }}</span>
                                    <span class="ml-2 px-1.5 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600">{{ $pv['code'] }}</span>
                                </div>
                            </td>
                            <td class="zems-table-td text-center font-bold text-slate-700">{{ $pv['teachers'] }}</td>
                            <td class="zems-table-td text-center font-bold text-blue-600">{{ $pv['lay'] ?: '-' }}</td>
                            <td class="zems-table-td text-center font-bold text-amber-600">{{ $pv['monk'] ?: '-' }}</td>
                            <td class="zems-table-td text-center font-black text-slate-900">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-800">
                                    {{ number_format($pv['total']) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function studentOverviewApp() {
    return {
        activeTab: 'enrollment',
        search: '',
        division: 'All',
        level: 'All',
        viewMode: 'summary',
        olSearch: '',
        olMode: 'total',
        alSearch: '',
        alMode: 'total',
        exportOpen: false,

        matchesSchool(name, census, div, cat) {
            if (this.search) {
                const q = this.search.toLowerCase().trim();
                const n = (name || '').toLowerCase();
                const c = (census || '').toLowerCase();
                if (!n.includes(q) && !c.includes(q)) {
                    return false;
                }
            }
            if (this.division !== 'All' && div !== this.division) {
                return false;
            }
            if (this.level !== 'All' && !(cat || '').includes(this.level)) {
                return false;
            }
            return true;
        },

        matchesSubject(query, subjSi, subjEn) {
            if (!query) return true;
            const q = query.toLowerCase().trim();
            const si = (subjSi || '').toLowerCase();
            const en = (subjEn || '').toLowerCase();
            return si.includes(q) || en.includes(q);
        }
    };
}
</script>
@endsection
