@extends('layouts.dashboard')
@section('title', $school->name)

@section('content')
<div class="space-y-6 animate-fade-in" x-data="schoolProfile()">
    {{-- Back Link & Actions --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('zonal.schools') }}" class="group flex items-center gap-2 text-slate-500 hover:text-slate-900 transition-all duration-200">
            <span class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-200 flex items-center justify-center group-hover:-translate-x-1 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </span>
            <span class="font-medium">Back to Schools</span>
        </a>

        <div class="flex items-center gap-3">
            <a href="#" onclick="alert('Coming soon!')" class="group flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-500 hover:to-indigo-500 text-sm font-semibold transition-all duration-200 shadow-lg shadow-purple-500/25 hover:shadow-xl hover:shadow-purple-500/30 hover:-translate-y-0.5">
                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Subject Statistics
            </a>
            <button onclick="document.getElementById('editSchoolModal').classList.remove('hidden')" class="group flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-500 hover:to-blue-600 text-sm font-semibold transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-0.5">
                <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit School
            </button>
            <form action="{{ route('zonal.schools.destroy', $school->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this school? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="group flex items-center gap-2 px-5 py-2.5 bg-white text-red-600 border border-red-200 rounded-xl hover:bg-red-50 hover:border-red-300 text-sm font-semibold transition-all duration-200">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- School Header --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-6 md:p-8 text-white shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative flex flex-col md:flex-row items-start gap-6">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-white/15 backdrop-blur-xl border border-white/20 flex items-center justify-center text-3xl md:text-4xl font-black shadow-2xl">
                {{ strtoupper(substr($school->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-2xl md:text-3xl font-black leading-tight mb-3">{{ $school->name }}</h1>
                <div class="flex flex-wrap items-center gap-2 md:gap-3">
                    @if($school->censusNo)
                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 backdrop-blur-sm rounded-lg text-xs font-bold uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Census: {{ $school->censusNo }}
                    </span>
                    @endif
                    @if($school->schoolType)
                    <span class="px-3 py-1.5 bg-amber-500/20 backdrop-blur-sm rounded-lg text-xs font-bold uppercase text-amber-100">
                        {{ $school->schoolType }}
                    </span>
                    @endif
                    <span class="px-3 py-1.5 bg-white/10 backdrop-blur-sm rounded-lg text-xs font-bold uppercase">
                        {{ $school->eduDivision ?? 'Wilgamuwa' }}
                    </span>
                    @if($school->dsDivision)
                    <span class="px-3 py-1.5 bg-indigo-500/20 backdrop-blur-sm rounded-lg text-xs font-bold uppercase text-indigo-100">
                        DSD: {{ $school->dsDivision }}
                    </span>
                    @endif
                    <span class="px-3 py-1.5 bg-emerald-500/25 backdrop-blur-sm rounded-lg text-xs font-bold text-emerald-100">
                        @php
                            $range = "Grade " . ($school->gradeStart ?? 1) . "-" . ($school->gradeEnd ?? 13);
                            $desc = $range;
                            if ($school->schoolType === '1AB') $desc = "Type 1AB - Senior Secondary ($range)";
                            elseif ($school->schoolType === '1C') $desc = "Type 1C - Senior Secondary ($range)";
                            elseif ($school->schoolType === 'Type II') $desc = "Type II - Junior School (Grade 1-11)";
                            elseif ($school->schoolType === 'Type III') $desc = "Type III - Primary School (Grade 1-5)";
                        @endphp
                        {{ $desc }}
                    </span>
                    @if($school->difficultyCategory)
                    <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $school->difficulty_badge_class }} backdrop-blur-sm shadow-sm flex items-center gap-1.5 border">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ $school->bilingual_difficulty }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Info Cards (4-column responsive grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Contact Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
            <h3 class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-4 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Contact Information
            </h3>
            <div class="space-y-4">
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Office Phone</span>
                    <p class="text-sm font-bold text-slate-900">{{ $school->contactNumber ?: 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Email</span>
                    <p class="text-sm font-bold text-slate-900">{{ $school->email ?: 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Address</span>
                    <p class="text-sm font-bold text-slate-900">{{ $school->address ?: 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Administration --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
            <h3 class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-4 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Administration
            </h3>
            <div class="space-y-4">
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Principal Name</span>
                    <p class="text-sm font-bold text-slate-900">{{ $school->principalName ?: 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Principal Mobile</span>
                    <p class="text-sm font-bold text-slate-900">{{ $school->principalMobile ?: 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Education Zone & Division</span>
                    <p class="text-sm font-bold text-slate-900">{{ $school->eduZone ?: 'Wilgamuwa' }} / {{ $school->eduDivision ?: 'Wilgamuwa' }}</p>
                </div>
            </div>
        </div>

        {{-- Geographic, Land & Location Details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full flex flex-col justify-between">
            <div>
                <h3 class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Geographic & Land Area
                </h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Land Area / ඉඩම් ප්‍රමාණය</span>
                        <p class="text-sm font-bold text-slate-900">{{ $school->landArea ?: 'Not Recorded' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Difficulty / දුෂ්කරතාව</span>
                        <div class="mt-0.5">
                            @if($school->difficultyCategory)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold border {{ $school->difficulty_badge_class }}">
                                {{ $school->bilingual_difficulty }}
                            </span>
                            @else
                            <p class="text-sm font-bold text-slate-400 italic">Not Set</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">DS Division (DSD) / ප්‍රා.ලේ.</span>
                        <p class="text-sm font-bold text-slate-900">{{ $school->dsDivision ?: 'Not Recorded' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">GN Division (GND) / ග්‍රා.නි.</span>
                        <p class="text-sm font-bold text-slate-900">{{ $school->gnDivision ?: 'Not Recorded' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Coordinates (Lat, Long)</span>
                        @if($school->latitude && $school->longitude)
                        <p class="text-xs font-mono font-bold text-slate-700">{{ number_format($school->latitude, 6) }}, {{ number_format($school->longitude, 6) }}</p>
                        @else
                        <p class="text-xs text-slate-400 italic">Not Recorded</p>
                        @endif
                    </div>
                </div>
            </div>
            @if($school->latitude && $school->longitude)
            <div class="pt-3 mt-3 border-t border-slate-100">
                <a href="https://www.google.com/maps?q={{ $school->latitude }},{{ $school->longitude }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1.5 w-full px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View on Google Maps</span>
                </a>
            </div>
            @endif
        </div>

        {{-- Staff Stats --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
            <h3 class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-4 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Staff Overview
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-xl">
                    <span class="text-sm font-medium text-indigo-700">Total Teachers</span>
                    <span class="text-2xl font-black text-indigo-600">{{ $school->teachers->count() }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-blue-50 rounded-xl text-center">
                        <p class="text-lg font-black text-blue-600">{{ $school->male_teachers_count }}</p>
                        <p class="text-[10px] font-bold uppercase text-blue-500">Male</p>
                    </div>
                    <div class="p-3 bg-pink-50 rounded-xl text-center">
                        <p class="text-lg font-black text-pink-600">{{ $school->female_teachers_count }}</p>
                        <p class="text-[10px] font-bold uppercase text-pink-500">Female</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Overview Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Student Overview ({{ date('Y') }})
            </h3>
            <div class="flex gap-2">
                <template x-if="!isEditingStudents">
                    <button @click="isEditingStudents = true" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Counts
                    </button>
                </template>
                <template x-if="isEditingStudents">
                    <div class="flex gap-2">
                        <button @click="isEditingStudents = false; resetData()" class="text-sm font-medium text-slate-500 hover:text-slate-700">Cancel</button>
                        <button @click="saveStudentCounts" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 1m0 0l-3-1m3 1v-7.5"/></svg>
                            Save
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- Grade Table --}}
        <div class="overflow-x-auto">
            <table class="zems-table min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="zems-table-header-row">
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Classes & Counts</th>
                        <th class="px-6 py-3 text-center text-[10px] font-black text-blue-500 uppercase tracking-wider w-24">Total Male</th>
                        <th class="px-6 py-3 text-center text-[10px] font-black text-pink-500 uppercase tracking-wider w-24">Total Female</th>
                        <th class="px-6 py-3 text-center text-[10px] font-black text-slate-500 uppercase tracking-wider w-24">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <template x-for="(grade, index) in grades" :key="grade">
                        <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50'">
                            <td class="px-6 py-4 font-bold text-slate-900 w-28 align-top">
                                <span x-text="grade"></span>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <template x-if="!isEditingStudents">
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="cls in getStudentData(grade).breakdown">
                                            <div class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs shadow-sm flex items-center gap-3">
                                                <span class="font-black text-slate-700 font-mono" x-text="cls.name || 'Unnamed'"></span>
                                                <div class="flex items-center gap-2 text-[11px] font-bold">
                                                    <span class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">M: <span x-text="cls.maleCount"></span></span>
                                                    <span class="text-pink-600 bg-pink-50 px-1.5 py-0.5 rounded">F: <span x-text="cls.femaleCount"></span></span>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="getStudentData(grade).breakdown.length === 0">
                                            <span class="text-slate-400 text-xs italic py-1.5">No classes added</span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="isEditingStudents">
                                    <div class="space-y-2">
                                        <template x-for="(cls, cIndex) in getStudentData(grade).breakdown" :key="cIndex">
                                            <div class="flex items-center gap-2 group">
                                                <div class="relative w-32">
                                                    <span class="absolute left-2.5 top-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider pointer-events-none">Class</span>
                                                    <input type="text" x-model="cls.name" @input="calculateTotal(grade)" class="w-full border border-slate-300 rounded-lg py-1.5 pl-12 pr-2 text-xs shadow-sm bg-white font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g. A, Bio">
                                                </div>
                                                <div class="relative w-24">
                                                    <span class="absolute left-2.5 top-2 text-[10px] font-bold text-blue-400 uppercase tracking-wider pointer-events-none">Male</span>
                                                    <input type="number" min="0" x-model.number="cls.maleCount" @input="calculateTotal(grade)" class="w-full border border-blue-100 rounded-lg py-1.5 pl-12 pr-2 text-xs text-center shadow-sm bg-blue-50/50 text-blue-700 font-bold focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder:text-blue-300" placeholder="0">
                                                </div>
                                                <div class="relative w-24">
                                                    <span class="absolute left-2.5 top-2 text-[10px] font-bold text-pink-400 uppercase tracking-wider pointer-events-none">Female</span>
                                                    <input type="number" min="0" x-model.number="cls.femaleCount" @input="calculateTotal(grade)" class="w-full border border-pink-100 rounded-lg py-1.5 pl-14 pr-2 text-xs text-center shadow-sm bg-pink-50/50 text-pink-700 font-bold focus:ring-2 focus:ring-pink-500 focus:outline-none placeholder:text-pink-300" placeholder="0">
                                                </div>
                                                <button @click="removeClass(grade, cIndex)" type="button" class="text-slate-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors ml-1" title="Remove Class">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button @click="addClass(grade)" type="button" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors border border-dashed border-indigo-200 mt-2">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Add Class to <span x-text="grade"></span>
                                        </button>
                                    </div>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <span class="text-sm font-black text-blue-600" x-text="getStudentData(grade).maleCount || 0"></span>
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <span class="text-sm font-black text-pink-600" x-text="getStudentData(grade).femaleCount || 0"></span>
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 font-black rounded-xl text-sm shadow-sm" x-text="(parseInt(getStudentData(grade).maleCount) || 0) + (parseInt(getStudentData(grade).femaleCount) || 0)"></span>
                            </td>
                        </tr>
                    </template>

                    {{-- Totals Row --}}
                    <tr class="bg-gradient-to-r from-slate-50 to-white">
                        <td colspan="2" class="px-6 py-4 text-right text-xs tracking-widest uppercase font-black text-slate-500">SCHOOL TOTALS</td>
                        <td class="px-6 py-4 text-center text-lg font-black text-blue-600" x-text="grades.reduce((sum, g) => sum + (getStudentData(g).maleCount || 0), 0)"></td>
                        <td class="px-6 py-4 text-center text-lg font-black text-pink-600" x-text="grades.reduce((sum, g) => sum + (getStudentData(g).femaleCount || 0), 0)"></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-4 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md font-black rounded-xl text-lg" x-text="grades.reduce((sum, g) => sum + (getStudentData(g).maleCount || 0) + (getStudentData(g).femaleCount || 0), 0)"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Teachers Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Teachers ({{ $school->teachers->count() }})
            </h3>
            <a href="{{ route('zonal.teachers', ['school' => $school->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                + Add Teacher
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Teacher</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">NIC</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Main Subject</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Joined</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($school->teachers as $teacher)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $teacher->is_male ? 'bg-blue-100 text-blue-600' : ($teacher->is_female ? 'bg-pink-100 text-pink-600' : 'bg-slate-100 text-slate-600') }}">
                                    {{ substr($teacher->fullName ?: $teacher->nameWithInitials, 0, 1) }}
                                </div>
                                <p class="text-sm font-bold text-slate-900">{{ $teacher->fullName ?: $teacher->nameWithInitials }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $teacher->nic }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $teacher->phone ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded border border-indigo-100">
                                {{ $teacher->position ?: ($teacher->currentPosition ?: 'Teacher') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded">
                                {{ $teacher->class_grade ?: ($teacher->serviceGrade ?: 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ $teacher->appt_code ?: ($teacher->appointmentSubject ?: 'N/A') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ ($teacher->schappoint ?? $teacher->joinedCurrentSchoolDate) ? \Carbon\Carbon::parse($teacher->schappoint ?? $teacher->joinedCurrentSchoolDate)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="py-12 text-center text-slate-400">No teachers assigned</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Physical Infrastructure & Buildings Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 sm:px-6 sm:py-4 border-b border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div class="flex items-center gap-2 flex-wrap">
                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-sm font-bold text-slate-900">Physical Infrastructure & Buildings ({{ $school->buildings->count() }})</h3>
                @if($school->sanitation)
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                    Toilets: {{ $school->sanitation->boysToilets }} Boys / {{ $school->sanitation->girlsToilets }} Girls / {{ $school->sanitation->teacherToilets }} Staff
                </span>
                @endif
            </div>
            <a href="{{ route('zonal.buildings', ['school_id' => $school->id]) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition w-full sm:w-auto justify-center">
                <span>Manage in Building Studio &rarr;</span>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-[640px] w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Building Name</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Floors</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Built</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Last Repair</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($school->buildings as $b)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3.5 text-xs font-bold text-slate-900">{{ $b->buildingName }}</td>
                        <td class="px-6 py-3.5 text-xs font-mono font-semibold text-slate-600">{{ $b->size ?: 'N/A' }}</td>
                        <td class="px-6 py-3.5 text-xs font-bold text-slate-700">{{ $b->floors }} {{ $b->floors > 1 ? 'Floors' : 'Floor' }}</td>
                        <td class="px-6 py-3.5 text-xs text-slate-700">{{ $b->constructionYear ?: 'N/A' }}</td>
                        <td class="px-6 py-3.5 text-xs text-slate-700">
                            @if($b->lastRepairYear)
                                {{ $b->lastRepairYear }}
                                @if($b->needsRenovation)
                                <span class="text-[9px] font-black text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded ml-1">&ge;10 yrs</span>
                                @endif
                            @else
                                <span class="text-amber-600 italic">No record</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-xs font-bold">
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-700">{{ $b->status ?: 'Good' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-xs text-slate-400">
                            No building records added for this school yet.
                            <a href="{{ route('zonal.buildings', ['school_id' => $school->id]) }}" class="text-blue-600 font-bold ml-1 hover:underline">+ Add Building</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Edit School Modal --}}
<div id="editSchoolModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10" onclick="document.getElementById('editSchoolModal').classList.add('hidden')"></div>
    <form method="POST" action="{{ route('zonal.schools.update', $school->id) }}" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-scale-in max-h-[90vh] flex flex-col relative z-20">
        @csrf
        @method('PUT')
        <div class="flex justify-between items-center px-6 py-4 bg-slate-50 border-b border-slate-200 shrink-0">
            <h3 class="font-bold text-xl text-slate-900">Edit School Information</h3>
            <button type="button" onclick="document.getElementById('editSchoolModal').classList.add('hidden')" class="p-1.5 hover:bg-slate-200 rounded-full transition-colors text-slate-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex flex-col gap-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="zems-input-label">School Name *</label>
                    <input type="text" name="name" value="{{ old('name', $school->name) }}" required class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">CENSUS No</label>
                    <input type="text" name="censusNo" value="{{ old('censusNo', $school->censusNo) }}" class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">School Type (Classification)</label>
                    <select name="schoolType" id="showSchoolType" onchange="updateShowGradeRange()" class="zems-input cursor-pointer">
                        <option value="">Select Type</option>
                        <optgroup label="Senior Secondary Schools (A/L)">
                            <option value="1AB" {{ old('schoolType', $school->schoolType) == '1AB' ? 'selected' : '' }}>Type 1AB - All Streams</option>
                            <option value="1C" {{ old('schoolType', $school->schoolType) == '1C' ? 'selected' : '' }}>Type 1C - Arts & Commerce Only</option>
                        </optgroup>
                        <optgroup label="Junior Schools">
                            <option value="Type II" {{ old('schoolType', $school->schoolType) == 'Type II' ? 'selected' : '' }}>Type II - Junior School (Grade 1-11)</option>
                        </optgroup>
                        <optgroup label="Primary Schools">
                            <option value="Type III" {{ old('schoolType', $school->schoolType) == 'Type III' ? 'selected' : '' }}>Type III - Primary School (Grade 1-5)</option>
                        </optgroup>
                    </select>
                </div>

                {{-- Grade Range --}}
                <div class="md:col-span-2 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <label class="block text-sm font-bold text-emerald-800 mb-3">Grade Range</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Starting Grade</label>
                            <select name="gradeStart" id="showGradeStart" onchange="updateShowInfoText()" class="zems-input cursor-pointer">
                                @for($g = 1; $g <= 13; $g++)
                                <option value="{{ $g }}" {{ old('gradeStart', $school->gradeStart ?? 1) == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Ending Grade</label>
                            <select name="gradeEnd" id="showGradeEnd" onchange="updateShowInfoText()" class="zems-input cursor-pointer">
                                @for($g = 1; $g <= 13; $g++)
                                <option value="{{ $g }}" {{ old('gradeEnd', $school->gradeEnd ?? 13) == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <p id="showInfoText" class="text-xs text-emerald-600 mt-2">
                        This school will show grades from <strong>Grade {{ old('gradeStart', $school->gradeStart ?? 1) }}</strong> to <strong>Grade {{ old('gradeEnd', $school->gradeEnd ?? 13) }}</strong>
                    </p>
                </div>

                {{-- Address & Land Area --}}
                <div>
                    <label class="zems-input-label">Address / ලිපිනය</label>
                    <input type="text" name="address" value="{{ old('address', $school->address) }}" class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">School Land Area / ඉඩම් ප්‍රමාණය</label>
                    <input type="text" name="landArea" value="{{ old('landArea', $school->landArea) }}" placeholder="e.g. 2 Acres 1 Rood 15 Perches" class="zems-input">
                </div>

                {{-- School Difficulty & Education Division --}}
                <div>
                    <label class="zems-input-label">School Difficulty Category / දුෂ්කරතා වර්ගීකරණය</label>
                    <select name="difficultyCategory" class="zems-input cursor-pointer font-semibold">
                        <option value="">Select Difficulty Category</option>
                        <option value="Very Convenient" {{ old('difficultyCategory', $school->difficultyCategory) == 'Very Convenient' ? 'selected' : '' }}>Very Convenient (ඉතා පහසු)</option>
                        <option value="Convenient" {{ old('difficultyCategory', $school->difficultyCategory) == 'Convenient' ? 'selected' : '' }}>Convenient (පහසු)</option>
                        <option value="Difficult" {{ old('difficultyCategory', $school->difficultyCategory) == 'Difficult' ? 'selected' : '' }}>Difficult (දුෂ්කර)</option>
                        <option value="Very Difficult" {{ old('difficultyCategory', $school->difficultyCategory) == 'Very Difficult' ? 'selected' : '' }}>Very Difficult (ඉතා දුෂ්කර)</option>
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">Education Division / අධ්‍යාපන කොට්ඨාසය</label>
                    <select name="eduDivision" class="zems-input cursor-pointer">
                        <option value="">Select Division</option>
                        <option value="Wilgamuwa" {{ old('eduDivision', $school->eduDivision) == 'Wilgamuwa' ? 'selected' : '' }}>Wilgamuwa (විල්ගමුව)</option>
                        <option value="Laggala" {{ old('eduDivision', $school->eduDivision) == 'Laggala' ? 'selected' : '' }}>Laggala (ලග්ගල)</option>
                    </select>
                </div>

                {{-- DS Division & Grama Niladhari Division --}}
                <div>
                    <label class="zems-input-label">Divisional Secretariat (DSD) / ප්‍රාදේශීය ලේකම් කොට්ඨාසය</label>
                    <select name="dsDivision" class="zems-input cursor-pointer font-semibold">
                        <option value="">Select DS Division</option>
                        <option value="Wilgamuwa" {{ old('dsDivision', $school->dsDivision) == 'Wilgamuwa' ? 'selected' : '' }}>Wilgamuwa (විල්ගමුව)</option>
                        <option value="Laggala-Pallegama" {{ old('dsDivision', $school->dsDivision) == 'Laggala-Pallegama' ? 'selected' : '' }}>Laggala-Pallegama (ලග්ගල පල්ලේගම)</option>
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">Grama Niladhari Division (GND) / ග්‍රාම නිලධාරී වසම</label>
                    <select name="gnDivision" class="zems-input cursor-pointer">
                        <option value="">Select GN Division</option>
                        <optgroup label="Wilgamuwa DS Division (39 GN Divisions)">
                            @foreach([
                                '80 Yaya' => '80 Yaya (E393D)',
                                'Aliyawala' => 'Aliyawala (E395L)',
                                'Aliwanguwa' => 'Aliwanguwa (E398G)',
                                'Bathgampola' => 'Bathgampola (E395D)',
                                'Dewagiriya' => 'Dewagiriya (E394F)',
                                'Dunuvilapitiya' => 'Dunuvilapitiya (E398F)',
                                'Gemburuoya' => 'Gemburuoya (E395K)',
                                'Guruwelayaya' => 'Guruwelayaya (E394D)',
                                'Handungamuwa' => 'Handungamuwa (E398)',
                                'Hettipola' => 'Hettipola (E394A)',
                                'Karawgahawewa' => 'Karawgahawewa (E395B)',
                                'Kumbukandana' => 'Kumbukandana (E398B)',
                                'Kumbukoya' => 'Kumbukoya (E398I)',
                                'Lediyangala' => 'Lediyangala (E395A)',
                                'Malgammana' => 'Malgammana (E395J)',
                                'Maraka' => 'Maraka (E395H)',
                                'Medakanda' => 'Medakanda (E398A)',
                                'Mewaobey' => 'Mewaobey (E393B)',
                                'Moragahaulpatha' => 'Moragahaulpatha (E395F)',
                                'Nagolla' => 'Nagolla (E395E)',
                                'Naminigama' => 'Naminigama (E393B)',
                                'Naminioya' => 'Naminioya (E394C)',
                                'Nugagolla' => 'Nugagolla (E393A)',
                                'Palupitiya' => 'Palupitiya (E398H)',
                                'Perakanaththa' => 'Perakanaththa (E395C)',
                                'Piduruwalla' => 'Piduruwalla (E394E)',
                                'Radunnewewa' => 'Radunnewewa (E395I)',
                                'Rattotayaya' => 'Rattotayaya (E398E)',
                                'Sonuththa' => 'Sonuththa (E393)',
                                'Sulugune' => 'Sulugune (E398L)',
                                'Thunhiriyawewa' => 'Thunhiriyawewa (E398C)',
                                'Topwalapitiya' => 'Topwalapitiya (E398J)',
                                'Uduwelwala' => 'Uduwelwala (E393C)',
                                'Veheragala' => 'Veheragala (E398D)',
                                'Viharagama' => 'Viharagama (E398K)',
                                'Wanarawa' => 'Wanarawa (E395G)',
                                'Wilgamuwa' => 'Wilgamuwa (E395)',
                            ] as $val => $label)
                            <option value="{{ $val }}" {{ old('gnDivision', $school->gnDivision) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Laggala-Pallegama DS Division (37 GN Divisions)">
                            @foreach([
                                'Akarahadiya' => 'Akarahadiya (E396B)',
                                'Atanwala' => 'Atanwala',
                                'Dammanthenna' => 'Dammanthenna',
                                'Dasgiriya' => 'Dasgiriya',
                                'Dewaladeniya' => 'Dewaladeniya',
                                'Diwulgaspathana' => 'Diwulgaspathana',
                                'Galgedawala' => 'Galgedawala (E396A)',
                                'Gangahenwala' => 'Gangahenwala',
                                'Gangala Puwakpitiya' => 'Gangala Puwakpitiya',
                                'Gonawala' => 'Gonawala (E397B)',
                                'Guruwela' => 'Guruwela (E397E)',
                                'Halminiya' => 'Halminiya',
                                'Haththotaamuna' => 'Haththotaamuna',
                                'Himbiliyakada' => 'Himbiliyakada (E396C)',
                                'Ilukkumbura' => 'Ilukkumbura',
                                'Imaduwa' => 'Imaduwa',
                                'Kahagala' => 'Kahagala',
                                'Kaluganga' => 'Kaluganga',
                                'Kandepitawala' => 'Kandepitawala',
                                'Karandamulla' => 'Karandamulla (E397C)',
                                'Kiyulewadiya' => 'Kiyulewadiya',
                                'Laggala Pallegama' => 'Laggala Pallegama (E397)',
                                'Lakegala' => 'Lakegala',
                                'Leloya' => 'Leloya (E396)',
                                'Mada Ela' => 'Mada Ela',
                                'Mahalakotuwa' => 'Mahalakotuwa',
                                'Maoya' => 'Maoya',
                                'Miniranketiya' => 'Miniranketiya (E397D)',
                                'Morathenna' => 'Morathenna',
                                'Narangamuwa' => 'Narangamuwa',
                                'Pitawala' => 'Pitawala',
                                'Poththatawela' => 'Poththatawela',
                                'Rambukoluwa' => 'Rambukoluwa',
                                'Ranamure' => 'Ranamure',
                                'Rathninda' => 'Rathninda',
                                'Rawanagama' => 'Rawanagama (E397A)',
                                'Waliwaranagolla' => 'Waliwaranagolla',
                                'Wallewala' => 'Wallewala',
                            ] as $val => $label)
                            <option value="{{ $val }}" {{ old('gnDivision', $school->gnDivision) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                {{-- Geographic Coordinates (Latitude & Longitude) --}}
                <div>
                    <label class="zems-input-label">Latitude / අක්ෂාංශය</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude', $school->latitude) }}" placeholder="e.g. 7.7123456" class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Longitude / දේශාංශය</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude', $school->longitude) }}" placeholder="e.g. 80.9123456" class="zems-input">
                </div>

                <div class="md:col-span-2">
                    <label class="zems-input-label">Education Zone / අධ්‍යාපන කලාපය</label>
                    <input type="text" name="eduZone" value="{{ old('eduZone', $school->eduZone ?? 'Wilgamuwa') }}" class="zems-input">
                </div>

                <div class="md:col-span-2 pt-4 border-t border-slate-200 mt-2">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-4 tracking-wide">Contact & Principal Information</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="zems-input-label">School Email</label>
                            <input type="email" name="email" value="{{ old('email', $school->email) }}" class="zems-input">
                        </div>
                        <div>
                            <label class="zems-input-label">School Phone</label>
                            <input type="text" name="contactNumber" value="{{ old('contactNumber', $school->contactNumber) }}" class="zems-input">
                        </div>
                        <div>
                            <label class="zems-input-label">Principal's Name</label>
                            <input type="text" name="principalName" value="{{ old('principalName', $school->principalName) }}" class="zems-input">
                        </div>
                        <div>
                            <label class="zems-input-label">Principal's NIC</label>
                            <input type="text" name="principalNic" value="{{ old('principalNic', $school->principalNic) }}" class="zems-input">
                        </div>
                        <div>
                            <label class="zems-input-label">Principal's Mobile</label>
                            <input type="text" name="principalMobile" value="{{ old('principalMobile', $school->principalMobile) }}" class="zems-input">
                        </div>
                        <div>
                            <label class="zems-input-label">Sanctioned Positions</label>
                            <input type="number" name="sanctionedPositions" value="{{ old('sanctionedPositions', $school->sanctionedPositions) }}" min="0" class="zems-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 shrink-0">
            <button type="button" onclick="document.getElementById('editSchoolModal').classList.add('hidden')" class="px-5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
            <button type="submit" class="zems-btn-primary py-2 px-6 shadow-md shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Record
            </button>
        </div>
    </form>
</div>

<script>
    function updateShowGradeRange() {
        const type = document.getElementById('showSchoolType').value;
        const gradeStart = document.getElementById('showGradeStart');
        const gradeEnd = document.getElementById('showGradeEnd');

        if (type === '1AB' || type === '1C') {
            gradeEnd.value = 13;
        } else if (type === 'Type II') {
            gradeStart.value = 1;
            gradeEnd.value = 11;
        } else if (type === 'Type III') {
            gradeStart.value = 1;
            gradeEnd.value = 5;
        }
        updateShowInfoText();
    }

    function updateShowInfoText() {
        const start = document.getElementById('showGradeStart').value;
        const end = document.getElementById('showGradeEnd').value;
        const textElement = document.getElementById('showInfoText');
        if (textElement) {
            textElement.innerHTML = `This school will show grades from <strong>Grade ${start}</strong> to <strong>Grade ${end}</strong>`;
        }
    }

    function schoolProfile() {
        return {
            isEditingStudents: false,
            // Grades strictly 1 to 13 as requested by user
            grades: [
                @for($i = 1; $i <= 13; $i++)
                    'Grade {{ $i }}',
                @endfor
            ],
            studentData: {},

            init() {
                const serverData = @json($school->studentCounts);
                
                this.grades.forEach(grade => {
                    const found = serverData.find(d => d.grade === grade);
                    
                    let breakdown = [];
                    if (found) {
                        try {
                            let parsedDetails = found.classDetails;
                            if (typeof parsedDetails === 'string' && parsedDetails.trim() !== '') {
                                parsedDetails = JSON.parse(parsedDetails);
                            }
                            if (Array.isArray(parsedDetails) && parsedDetails.length > 0) {
                                breakdown = parsedDetails.map(item => ({
                                    name: item.name || '',
                                    maleCount: item.maleCount || item.male || 0,
                                    femaleCount: item.femaleCount || item.female || 0
                                }));
                            }
                        } catch (e) {
                            console.error("Error parsing classDetails", e);
                        }

                        // If they had legacy data without a breakdown, migrate it into a single class "A"
                        if (breakdown.length === 0 && (found.maleCount > 0 || found.femaleCount > 0)) {
                            // Only 1 class
                            breakdown.push({
                                name: found.classes ? found.classes.split(',')[0].trim() : 'A',
                                maleCount: found.maleCount || 0,
                                femaleCount: found.femaleCount || 0
                            });
                        }
                    }

                    this.studentData[grade] = {
                        classes: found ? found.classes || '' : '',
                        maleCount: found ? found.maleCount || 0 : 0,
                        femaleCount: found ? found.femaleCount || 0 : 0,
                        breakdown: breakdown
                    };
                });
            },

            getStudentData(grade) {
                if (!this.studentData[grade]) {
                     this.studentData[grade] = { classes: '', maleCount: 0, femaleCount: 0, breakdown: [] };
                }
                return this.studentData[grade];
            },

            addClass(grade) {
                const data = this.studentData[grade];
                // Suggest next letter (A, B, C...)
                const nextChar = String.fromCharCode(65 + data.breakdown.length);
                data.breakdown.push({ name: nextChar, maleCount: 0, femaleCount: 0 });
                this.calculateTotal(grade);
            },

            removeClass(grade, index) {
                if (confirm('Are you sure you want to remove this class?')) {
                    const data = this.studentData[grade];
                    data.breakdown.splice(index, 1);
                    this.calculateTotal(grade);
                }
            },

            calculateTotal(grade) {
                this.$nextTick(() => {
                    const data = this.studentData[grade];
                    if (data.breakdown) {
                        data.classes = data.breakdown.map(c => c.name).filter(Boolean).join(', ');
                        data.maleCount = data.breakdown.reduce((sum, cls) => sum + (parseInt(cls.maleCount) || 0), 0);
                        data.femaleCount = data.breakdown.reduce((sum, cls) => sum + (parseInt(cls.femaleCount) || 0), 0);
                    }
                });
            },

            saveStudentCounts() {
                this.grades.forEach(grade => this.calculateTotal(grade));

                const countsArray = this.grades.map(grade => ({
                    grade: grade,
                    classCount: this.studentData[grade].breakdown.length,
                    classes: this.studentData[grade].classes,
                    maleCount: this.studentData[grade].maleCount,
                    femaleCount: this.studentData[grade].femaleCount,
                    classDetails: this.studentData[grade].breakdown.map(cls => ({
                        name: cls.name,
                        maleCount: cls.maleCount,
                        femaleCount: cls.femaleCount
                    }))
                }));

                // Use fetch to submit
                fetch('{{ route("zonal.schools.update-counts", $school->id) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ counts: countsArray })
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Failed to save data');
                    }
                });
            },
            
            resetData() {
                this.init();
            }
        }
    }
</script>
@endsection