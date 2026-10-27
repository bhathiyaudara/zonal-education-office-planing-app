@extends('layouts.dashboard')
@section('title', 'Manage Teachers')

@section('content')
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
            <button onclick="exportTeachers()" class="zems-btn-header">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export
            </button>
            <a href="{{ route('zonal.teachers.create') }}" class="zems-btn-primary !bg-white !text-blue-700 hover:!bg-blue-50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Teacher
            </a>
        </div>
    </div>

    {{-- Summary Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Active Pool --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Pool</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalTeachers }}</p>
            </div>
        </div>

        {{-- Male / Female --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Male / Female</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $maleCount }} <span class="text-slate-300 text-lg">/</span> {{ $femaleCount }}</p>
            </div>
        </div>

        {{-- Retiring Soon --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Retiring Soon</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-2xl font-black text-slate-900">{{ $retiringCount }}</p>
                    <p class="text-xs font-bold text-orange-500 bg-orange-100 px-2 py-0.5 rounded-md">Dalam 5 Tahun</p>
                </div>
            </div>
        </div>

        {{-- Principals (SLPS) --}}
        <div class="zems-card-base p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Principals (SLPS)</p>
                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-500">Grade III:</span>
                        <span class="font-black text-slate-900">{{ $slpsGrade3 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-500">Grade II:</span>
                        <span class="font-black text-slate-900">{{ $slpsGrade2 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-500">Grade I:</span>
                        <span class="font-black text-slate-900">{{ $slpsGrade1 }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1 border-t border-slate-100 mt-1">
                        <span class="font-black text-slate-700 text-xs">Total:</span>
                        <span class="font-black text-purple-600 text-sm">{{ $totalPrincipals }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="zems-card-base p-5">
        <div class="flex gap-4 items-center flex-col sm:flex-row">
            <div class="relative flex-1 w-full">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="teacherSearchInput" placeholder="Search by Name or NIC Number..."
                       class="zems-input pl-12 h-[46px] w-full">
            </div>
            <button id="filtersToggleBtn" onclick="toggleFilters()" class="zems-btn-secondary h-[46px] w-full sm:w-auto uppercase tracking-wide text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filters
            </button>
        </div>

        {{-- Advanced Filters (hidden by default) --}}
        <div id="advancedFilters" style="display:none;" class="pt-5 mt-5 border-t border-slate-100 hidden-transition">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="zems-input-label">Assigned Institution</label>
                    <select id="filterSchool" class="zems-input">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">Staff Gender</label>
                    <select id="filterGender" class="zems-input">
                        <option value="">All Genders</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">Current Position</label>
                    <select id="filterPosition" class="zems-input">
                        <option value="">All Positions</option>
                        <option value="Principal">Principal</option>
                        <option value="Deputy Principal">Deputy Principal</option>
                        <option value="Assistant Principal">Assistant Principal</option>
                        <option value="Teacher">Teacher</option>
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">Service Grade</label>
                    <select id="filterGrade" class="zems-input">
                        <option value="">All Grades</option>
                        <option value="SLPS Grade III">SLPS Grade III</option>
                        <option value="SLPS Grade II">SLPS Grade II</option>
                        <option value="SLPS Grade I">SLPS Grade I</option>
                        <option value="SLTS Grade III">SLTS Grade III</option>
                        <option value="SLTS Grade II">SLTS Grade II</option>
                        <option value="SLTS Grade I">SLTS Grade I</option>
                    </select>
                </div>
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
                        <th class="zems-table-th">Unit</th>
                        <th class="zems-table-th">Classification</th>
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
                        $incrBase = $teacher->serviceGradeAppointedDate ?? $teacher->appointmentDate;
                        if ($incrBase) {
                            $nextIncrement = $incrBase->copy()->setYear(now()->year);
                            if ($nextIncrement->lt(now())) {
                                $nextIncrement->addYear();
                            }
                        }

                        $isPrincipal = in_array($teacher->currentPosition, ['Principal', 'Deputy Principal', 'Assistant Principal']);
                    @endphp
                    <tr class="teacher-row zems-table-row"
                        data-name="{{ strtolower($teacher->nameWithInitials . ' ' . $teacher->fullName) }}"
                        data-nic="{{ strtolower($teacher->nic) }}"
                        data-school="{{ $teacher->schoolId }}"
                        data-gender="{{ $teacher->gender }}"
                        data-position="{{ $teacher->currentPosition }}"
                        data-grade="{{ $teacher->serviceGrade }}">
                        {{-- Staff Identity --}}
                        <td class="zems-table-td">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-black text-sm shrink-0">
                                    {{ strtoupper(substr($teacher->fullName ?? 'T', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 leading-tight">{{ $teacher->nameWithInitials }}</div>
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
                        </td>

                        {{-- Classification --}}
                        <td class="zems-table-td">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex w-max px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $isPrincipal ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-white' }}">
                                    {{ $teacher->currentPosition ?? 'Teacher' }}
                                </span>
                                <div class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">
                                    {{ $teacher->serviceGrade ?? '' }}{{ $teacher->serviceGrade && $teacher->appointmentType ? ' • ' : '' }}{{ $teacher->appointmentType ?? '' }}
                                </div>
                            </div>
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
                                <button @click="open('{{ route('zonal.teachers.destroy', $teacher->id) }}', '{{ addslashes($teacher->nameWithInitials) }}')"
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
    const searchInput = document.getElementById('teacherSearchInput');
    const filterSchool = document.getElementById('filterSchool');
    const filterGender = document.getElementById('filterGender');
    const filterPosition = document.getElementById('filterPosition');
    const filterGrade = document.getElementById('filterGrade');
    const rows = document.querySelectorAll('.teacher-row');
    const noResults = document.getElementById('teacherNoResults');

    function filterTeachers() {
        const search = searchInput.value.toLowerCase().trim();
        const school = filterSchool.value;
        const gender = filterGender.value;
        const position = filterPosition.value;
        const grade = filterGrade.value;
        let visibleCount = 0;

        rows.forEach(function(row) {
            const name = row.getAttribute('data-name') || '';
            const nic = row.getAttribute('data-nic') || '';
            const rowSchool = row.getAttribute('data-school') || '';
            const rowGender = row.getAttribute('data-gender') || '';
            const rowPosition = row.getAttribute('data-position') || '';
            const rowGrade = row.getAttribute('data-grade') || '';

            const matchesSearch = !search || name.includes(search) || nic.includes(search);
            const matchesSchool = !school || rowSchool === school;
            const matchesGender = !gender || rowGender === gender;
            const matchesPosition = !position || rowPosition === position;
            const matchesGrade = !grade || rowGrade === grade;

            if (matchesSearch && matchesSchool && matchesGender && matchesPosition && matchesGrade) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }

    searchInput.addEventListener('input', filterTeachers);
    filterSchool.addEventListener('change', filterTeachers);
    filterGender.addEventListener('change', filterTeachers);
    filterPosition.addEventListener('change', filterTeachers);
    filterGrade.addEventListener('change', filterTeachers);
});

function toggleFilters() {
    const filters = document.getElementById('advancedFilters');
    const btn = document.getElementById('filtersToggleBtn');
    if (filters.style.display === 'none') {
        filters.style.display = '';
        btn.style.background = '#0f172a';
        btn.style.color = '#fff';
        btn.style.borderColor = '#0f172a';
    } else {
        filters.style.display = 'none';
        btn.style.background = '#fff';
        btn.style.color = '#475569';
        btn.style.borderColor = '#e2e8f0';
    }
}

function resetFilters() {
    document.getElementById('teacherSearchInput').value = '';
    document.getElementById('filterSchool').value = '';
    document.getElementById('filterGender').value = '';
    document.getElementById('filterPosition').value = '';
    document.getElementById('filterGrade').value = '';
    document.querySelectorAll('.teacher-row').forEach(function(row) { row.style.display = ''; });
    document.getElementById('teacherNoResults').style.display = 'none';
}

function exportTeachers() {
    const rows = document.querySelectorAll('.teacher-row');
    let csv = 'Name,NIC,Gender,School,Position,Service Grade\n';
    rows.forEach(function(row) {
        if (row.style.display === 'none') return;
        const cells = row.querySelectorAll('td');
        const name = cells[0].querySelector('div > div > div').textContent.trim();
        const nic = cells[1].querySelector('div').textContent.trim();
        const gender = row.getAttribute('data-gender');
        const school = cells[2].querySelector('span') ? cells[2].querySelector('span').textContent.trim() : 'N/A';
        const position = row.getAttribute('data-position') || 'Teacher';
        const grade = row.getAttribute('data-grade') || 'N/A';
        csv += `"${name}","${nic}","${gender}","${school}","${position}","${grade}"\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.setAttribute('href', URL.createObjectURL(blob));
    link.setAttribute('download', 'Teachers_Report_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
