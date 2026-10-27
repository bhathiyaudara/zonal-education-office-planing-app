@extends('layouts.dashboard')

@section('title', 'Remove Teacher')

@section('content')
<div class="space-y-6 animate-fade-in"
    x-data="{
        query: '',
        results: [],
        loading: false,
        isOpen: false,
        selectedTeacherId: null,
        selectedTeacherName: '',
        selectedSchoolName: '',
        fetchResults() {
            if (this.query.length < 2) {
                this.results = [];
                this.isOpen = false;
                return;
            }
            this.loading = true;
            fetch(`{{ route('zonal.teachers.search') }}?query=${encodeURIComponent(this.query)}`)
                .then(res => res.json())
                .then(data => {
                    this.results = data;
                    this.isOpen = true;
                    this.loading = false;
                });
        },
        selectTeacher(teacher) {
            this.selectedTeacherId = teacher.id;
            this.selectedTeacherName = teacher.nameWithInitials;
            this.selectedSchoolName = teacher.school ? teacher.school.name : 'Unassigned';
            this.query = teacher.nic;
            this.isOpen = false;
        }
    }">

    {{-- Header Banner --}}
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h1 class="zems-header-title">Remove Teacher</h1>
                <p class="zems-header-subtitle">Permanently remove a teacher record from the zonal database</p>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-900 border border-emerald-200 p-4 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="font-medium">Success</h3>
                <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 text-red-900 border border-red-200 p-4 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3 class="font-medium">Error</h3>
                <ul class="text-sm text-red-700 mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Container --}}
    <form method="POST" action="{{ route('zonal.teachers.remove.submit') }}" class="zems-card-base p-6 sm:p-8 space-y-8 max-w-3xl">
        @csrf
        
        {{-- Hidden Input --}}
        <input type="hidden" name="teacher_id" x-model="selectedTeacherId" required>

        <div class="space-y-8">
            {{-- Search Input --}}
            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] items-center gap-4">
                <label class="zems-input-label mb-0">Search NIC</label>
                <div class="relative" @click.away="isOpen = false">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                        x-model="query" 
                        @input.debounce.300ms="fetchResults"
                        @focus="if(results.length > 0) isOpen = true"
                        placeholder="Enter NIC or Name..."
                        class="zems-input pl-10"
                        autocomplete="off">

                    {{-- Loading Spinner --}}
                    <div x-show="loading" class="absolute right-3 top-2.5 text-slate-400">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    {{-- Dropdown --}}
                    <div x-show="isOpen && results.length > 0" 
                         x-transition
                         class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                        <template x-for="teacher in results" :key="teacher.id">
                            <button type="button" 
                                @click="selectTeacher(teacher)"
                                class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-100 last:border-0 transition-colors focus:bg-slate-50">
                                <div class="font-medium text-slate-900" x-text="teacher.nameWithInitials"></div>
                                <div class="text-xs text-slate-500 mt-0.5 flex gap-2">
                                    <span x-text="'NIC: ' + teacher.nic"></span>
                                    <span>•</span>
                                    <span x-text="teacher.school ? teacher.school.name : 'Unassigned'"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Readonly Fields --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 rounded-2xl bg-slate-50 border border-slate-100/50">
                <div class="space-y-1">
                    <label class="zems-input-label mb-0">Teacher Name</label>
                    <div class="zems-input bg-slate-100/50 flex items-center min-h-[44px]" 
                         x-text="selectedTeacherName || '-'"></div>
                </div>
                <div class="space-y-1">
                    <label class="zems-input-label mb-0">School</label>
                    <div class="zems-input bg-slate-100/50 flex items-center min-h-[44px]" 
                         x-text="selectedSchoolName || '-'"></div>
                </div>
            </div>

            {{-- Reason and Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] items-center gap-4">
                <label class="zems-input-label mb-0">Reason for Removal</label>
                <select name="reason" required class="zems-input">
                    <option value="">-- Select Reason --</option>
                    <option value="Retirement">Retirement</option>
                    <option value="Resignation">Resignation</option>
                    <option value="Transfer out of Zone">Transfer out of Zone</option>
                    <option value="Dismissal">Dismissal</option>
                    <option value="Death">Death</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] items-center gap-4">
                <label class="zems-input-label mb-0">Effective Date</label>
                <div class="relative w-full sm:w-64">
                    <input type="date" name="effective_date" required class="zems-input">
                </div>
            </div>
            
            <hr class="border-slate-100 my-8">

            {{-- Warning Box --}}
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3">
                <div class="p-2 bg-red-100 rounded-lg h-fit">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-red-900 text-sm">Irreversible Action</h4>
                    <p class="text-sm text-red-700 mt-1">This action will permanently delete the teacher's record, including all service history and subject assignments. This cannot be undone.</p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button type="submit" 
                    :disabled="!selectedTeacherId"
                    class="zems-btn-primary !bg-red-600 hover:!bg-red-700 w-fit px-8 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Remove Teacher Record
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
