@extends('layouts.dashboard')
@section('title', 'Buildings & Physical Facilities')

@section('content')
<div class="space-y-6 animate-fade-in relative z-20" x-data="schoolBuildingManager()">

    {{-- Gradient Header --}}
    <div class="zems-header !p-4 sm:!p-6 md:!p-8">
        <div class="flex items-start gap-3 sm:gap-4 w-full sm:w-auto">
            <div class="hidden sm:flex w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center border border-white/10 shadow-lg shrink-0">
                <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-white leading-tight tracking-tight break-words">{{ $school->name }} &bull; Buildings & Facilities</h1>
                <p class="text-xs sm:text-sm text-blue-100 mt-1">Manage physical buildings, dimensions (Length &times; Width), categories & sanitation facilities</p>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto shrink-0">
            <button type="button" @click="openCreateModal()" class="zems-btn-primary !bg-white !text-blue-700 hover:!bg-blue-50 text-xs sm:text-sm !py-2.5 !px-4 w-full sm:w-auto justify-center shadow-md">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Building</span>
            </button>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('success'))
    <div class="p-3.5 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold px-1">&times;</button>
    </div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
        {{-- Total Buildings --}}
        <div class="zems-card-base p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Buildings</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ $buildings->count() }}</p>
                <span class="text-[10px] text-slate-400 font-semibold truncate block">In this school</span>
            </div>
        </div>

        {{-- Needs Renovation (>=10 Yrs) --}}
        <div class="zems-card-base p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Needs Renovation</p>
                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-amber-100 text-amber-800">&ge; 10 Yrs</span>
                </div>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ $renovationCount }}</p>
                <span class="text-[10px] text-amber-600 font-semibold truncate block">Priority repair list</span>
            </div>
        </div>

        {{-- Multi-Floor Structures --}}
        <div class="zems-card-base p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Multi-Story Buildings</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ $multiFloorCount }}</p>
                <span class="text-[10px] text-purple-600 font-semibold truncate block">2+ Floors</span>
            </div>
        </div>

        {{-- Total Sq.ft --}}
        <div class="zems-card-base p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Area (Floor Space)</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ number_format($totalSqft) }} <span class="text-xs font-normal text-slate-500">sqft</span></p>
                <span class="text-[10px] text-emerald-600 font-semibold truncate block">Calculated floor area</span>
            </div>
        </div>
    </div>

    {{-- Sanitation / Toilets Panel --}}
    <div class="zems-card-base p-4 sm:p-5 space-y-3 bg-gradient-to-r from-blue-50/70 via-indigo-50/50 to-white">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h3 class="text-sm font-black text-slate-900">Sanitation Facilities & Norms (වැසිකිලි පහසුකම්)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Track both available units and required units according to ministry norms</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-black text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">
                    Available: {{ ($sanitation->boysToilets ?? 0) + ($sanitation->girlsToilets ?? 0) + ($sanitation->teacherToilets ?? 0) }} Units
                </span>
                <span class="text-xs font-black text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full">
                    Required: {{ ($sanitation->reqBoysToilets ?? 0) + ($sanitation->reqGirlsToilets ?? 0) + ($sanitation->reqTeacherToilets ?? 0) }} Units
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('school.sanitation.update') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pt-2 border-t border-slate-200">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                {{-- Available Units --}}
                <div class="bg-white border border-emerald-200 rounded-xl p-2.5 shadow-sm space-y-1">
                    <span class="text-[10px] font-black text-emerald-800 uppercase tracking-wider block">Available for Use (භාවිතයට ඇති ප්‍රමාණය)</span>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex flex-col text-[10px] font-bold text-slate-600">
                            Boys:
                            <input type="number" min="0" name="boysToilets" value="{{ $sanitation->boysToilets ?? 0 }}" class="zems-input !h-8 text-center font-bold text-xs mt-0.5">
                        </label>
                        <label class="flex flex-col text-[10px] font-bold text-slate-600">
                            Girls:
                            <input type="number" min="0" name="girlsToilets" value="{{ $sanitation->girlsToilets ?? 0 }}" class="zems-input !h-8 text-center font-bold text-xs mt-0.5">
                        </label>
                        <label class="flex flex-col text-[10px] font-bold text-slate-600">
                            Staff:
                            <input type="number" min="0" name="teacherToilets" value="{{ $sanitation->teacherToilets ?? 0 }}" class="zems-input !h-8 text-center font-bold text-xs mt-0.5">
                        </label>
                    </div>
                </div>

                {{-- Required Norms --}}
                <div class="bg-white border border-blue-200 rounded-xl p-2.5 shadow-sm space-y-1">
                    <span class="text-[10px] font-black text-blue-800 uppercase tracking-wider block">Required Norms (ප්‍රමිති අනුව අවශ්‍ය ප්‍රමාණය)</span>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex flex-col text-[10px] font-bold text-slate-600">
                            Boys:
                            <input type="number" min="0" name="reqBoysToilets" value="{{ $sanitation->reqBoysToilets ?? 0 }}" class="zems-input !h-8 text-center font-bold text-xs mt-0.5">
                        </label>
                        <label class="flex flex-col text-[10px] font-bold text-slate-600">
                            Girls:
                            <input type="number" min="0" name="reqGirlsToilets" value="{{ $sanitation->reqGirlsToilets ?? 0 }}" class="zems-input !h-8 text-center font-bold text-xs mt-0.5">
                        </label>
                        <label class="flex flex-col text-[10px] font-bold text-slate-600">
                            Staff:
                            <input type="number" min="0" name="reqTeacherToilets" value="{{ $sanitation->reqTeacherToilets ?? 0 }}" class="zems-input !h-8 text-center font-bold text-xs mt-0.5">
                        </label>
                    </div>
                </div>
            </div>

            <div class="shrink-0 flex items-end">
                <button type="submit" class="zems-btn-primary h-10 px-5 text-xs font-bold w-full sm:w-auto justify-center shadow-md">
                    Update Sanitation
                </button>
            </div>
        </form>
    </div>

    {{-- Buildings Mobile Card View (Shown on screens < 768px) --}}
    <div class="block md:hidden space-y-3">
        @forelse($buildings as $b)
        @php
            $age = $b->constructionYear ? (date('Y') - $b->constructionYear) : null;
            $yearsSinceRepair = $b->lastRepairYear ? (date('Y') - $b->lastRepairYear) : null;
            $needsRenovation = !$b->lastRepairYear || $yearsSinceRepair >= 10;
            
            // Category badges
            $typeClass = 'bg-blue-50 text-blue-700 border-blue-200';
            if (stripos($b->buildingType, 'Admin') !== false || stripos($b->buildingType, 'පරිපාලන') !== false) {
                $typeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            } elseif (stripos($b->buildingType, 'Teachers') !== false || stripos($b->buildingType, 'ගුරු') !== false) {
                $typeClass = 'bg-amber-50 text-amber-700 border-amber-200';
            } elseif (stripos($b->buildingType, 'Principal') !== false || stripos($b->buildingType, 'විදුහල්පති') !== false) {
                $typeClass = 'bg-purple-50 text-purple-700 border-purple-200';
            } elseif (stripos($b->buildingType, 'Other') !== false || stripos($b->buildingType, 'වෙනත්') !== false) {
                $typeClass = 'bg-slate-100 text-slate-700 border-slate-200';
            }

            // Condition status
            $bilingualStatus = $b->bilingual_status;
            $statusClass = 'bg-slate-100 text-slate-800 border-slate-200';
            if (stripos($bilingualStatus, 'Good') !== false) {
                $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            } elseif (stripos($bilingualStatus, 'Minor') !== false) {
                $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
            } elseif (stripos($bilingualStatus, 'Major') !== false) {
                $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
            } elseif (stripos($bilingualStatus, 'Dilapidated') !== false || stripos($bilingualStatus, 'Dangerous') !== false || stripos($bilingualStatus, 'Condemn') !== false) {
                $statusClass = 'bg-rose-50 text-rose-700 border-rose-200';
            }
        @endphp
        <div class="zems-card-base p-4 space-y-3">
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-start gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-xl {{ $needsRenovation ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700' }} flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-slate-900 text-sm leading-snug break-words">{{ $b->buildingName }}</h4>
                        <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                            <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $typeClass }}">
                                {{ $b->buildingType ?: 'Learning / ඉගෙනුම්' }}
                            </span>
                            @if($b->floors > 1)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">{{ $b->floors }} Floors</span>
                            @endif
                        </div>
                    </div>
                </div>
                <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold border shrink-0 {{ $statusClass }}">
                    {{ $bilingualStatus }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2 bg-slate-50/80 rounded-xl p-2.5 border border-slate-100 text-xs">
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Dimensions</span>
                    @if($b->lengthFeet && $b->widthFeet)
                        <span class="font-mono font-bold text-slate-800">{{ $b->lengthFeet }}' &times; {{ $b->widthFeet }}'</span>
                        @if($b->areaSqft)
                        <span class="text-[10px] text-slate-500 block">({{ number_format($b->areaSqft) }} sqft)</span>
                        @endif
                    @else
                        <span class="font-mono font-bold text-slate-800">{{ $b->size ?: 'N/A' }}</span>
                    @endif
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Year Built / Age</span>
                    <span class="font-bold text-slate-800">{{ $b->constructionYear ? $b->constructionYear . ' (' . $age . ' yrs)' : 'Unknown' }}</span>
                </div>
                <div class="col-span-2">
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Last Repair</span>
                    @if($b->lastRepairYear)
                        <span class="font-bold text-slate-800">{{ $b->lastRepairYear }}</span>
                        @if($needsRenovation)
                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-amber-700 bg-amber-100 px-1.5 py-0.2 rounded ml-1">⚠️ &ge;10 Yrs</span>
                        @else
                        <span class="text-[10px] text-emerald-600 font-bold ml-1">({{ $yearsSinceRepair }} yrs ago)</span>
                        @endif
                    @else
                        <span class="text-amber-700 text-xs font-bold">No repair record</span>
                    @endif
                </div>
                @if($b->notes && !str_starts_with($b->notes, 'Imported') && !str_starts_with($b->notes, 'Wilgamuwa'))
                <div class="col-span-2 text-[11px] text-slate-600 bg-white p-2 rounded-lg border border-slate-200">
                    <b class="text-slate-800">Remarks:</b> {{ $b->notes }}
                </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100">
                <button type="button" @click="openEditModal({{ json_encode($b) }})" class="zems-btn-secondary text-xs !py-1.5 px-3">
                    Edit
                </button>
                <button type="button" @click="openDeleteModal(@js(route('school.buildings.destroy', $b->id)), @js($b->buildingName))" class="zems-btn-secondary !text-rose-600 hover:!bg-rose-50 text-xs !py-1.5 px-3">
                    Delete
                </button>
            </div>
        </div>
        @empty
        <div class="zems-card-base p-8 text-center text-slate-400">
            <svg class="w-12 h-12 opacity-20 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="text-xs font-black uppercase tracking-wider text-slate-500">No building records added yet</p>
            <button type="button" @click="openCreateModal()" class="text-xs font-bold text-blue-600 hover:underline mt-2 inline-block">
                + Add First Building
            </button>
        </div>
        @endforelse
    </div>

    {{-- Buildings Desktop Table View (Shown on screens >= 768px) --}}
    <div class="zems-table-container hidden md:block">
        <div class="overflow-x-auto w-full">
            <table class="zems-table min-w-full">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th">Building Name & Category</th>
                        <th class="zems-table-th">Dimensions & Area</th>
                        <th class="zems-table-th">Floors</th>
                        <th class="zems-table-th">Year Built</th>
                        <th class="zems-table-th">Last Repair</th>
                        <th class="zems-table-th">Structural Condition / තත්ත්වය</th>
                        <th class="zems-table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildings as $b)
                    @php
                        $age = $b->constructionYear ? (date('Y') - $b->constructionYear) : null;
                        $yearsSinceRepair = $b->lastRepairYear ? (date('Y') - $b->lastRepairYear) : null;
                        $needsRenovation = !$b->lastRepairYear || $yearsSinceRepair >= 10;

                        // Category badges
                        $typeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                        if (stripos($b->buildingType, 'Admin') !== false || stripos($b->buildingType, 'පරිපාලන') !== false) {
                            $typeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        } elseif (stripos($b->buildingType, 'Teachers') !== false || stripos($b->buildingType, 'ගුරු') !== false) {
                            $typeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                        } elseif (stripos($b->buildingType, 'Principal') !== false || stripos($b->buildingType, 'විදුහල්පති') !== false) {
                            $typeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                        } elseif (stripos($b->buildingType, 'Other') !== false || stripos($b->buildingType, 'වෙනත්') !== false) {
                            $typeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                        }

                        // Status pill
                        $statusClass = 'bg-slate-100 text-slate-800 border-slate-200';
                        if (stripos($b->status, 'Good') !== false || stripos($b->status, 'හොද') !== false || stripos($b->status, 'භාවිතා') !== false) {
                            $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        } elseif (stripos($b->status, 'Minor') !== false || stripos($b->status, 'සාමාන්‍ය') !== false) {
                            $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                        } elseif (stripos($b->status, 'Major') !== false || stripos($b->status, 'අලුත්වැඩියා') !== false) {
                            $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
                        } elseif (stripos($b->status, 'Danger') !== false || stripos($b->status, 'අබලන්') !== false || stripos($b->status, 'Condemn') !== false) {
                            $statusClass = 'bg-rose-50 text-rose-700 border-rose-200';
                        }
                    @endphp
                    <tr class="zems-table-row hover:bg-slate-50/80 transition">
                        <td class="zems-table-td">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $needsRenovation ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700' }} flex items-center justify-center font-black text-sm shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 leading-tight">{{ $b->buildingName }}</div>
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold border {{ $typeClass }}">
                                            {{ $b->buildingType ?: 'Learning / ඉගෙනුම්' }}
                                        </span>
                                    </div>
                                    @if($b->notes && !str_starts_with($b->notes, 'Imported') && !str_starts_with($b->notes, 'Wilgamuwa'))
                                    <div class="text-[11px] text-slate-400 font-medium truncate max-w-xs mt-0.5">{{ $b->notes }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="zems-table-td">
                            @if($b->lengthFeet && $b->widthFeet)
                                <div class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded inline-block">
                                    {{ $b->lengthFeet }}' &times; {{ $b->widthFeet }}'
                                </div>
                                @if($b->areaSqft)
                                <div class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ number_format($b->areaSqft) }} sq.ft</div>
                                @endif
                            @else
                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $b->size ?: 'N/A' }}
                                </span>
                            @endif
                        </td>

                        <td class="zems-table-td">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $b->floors > 1 ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $b->floors }} {{ $b->floors > 1 ? 'Floors' : 'Floor' }}
                            </span>
                        </td>

                        <td class="zems-table-td">
                            @if($b->constructionYear)
                            <div class="text-xs font-bold text-slate-800">{{ $b->constructionYear }}</div>
                            <div class="text-[10px] text-slate-400 font-semibold">{{ $age }} yrs ago</div>
                            @else
                            <span class="text-slate-400 text-xs italic">Unknown</span>
                            @endif
                        </td>

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

                        <td class="zems-table-td">
                            @php
                                $bilingualStatus = $b->bilingual_status;
                                $statusClass = 'bg-slate-100 text-slate-800 border-slate-200';
                                if (stripos($bilingualStatus, 'Good') !== false) {
                                    $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                } elseif (stripos($bilingualStatus, 'Minor') !== false) {
                                    $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                } elseif (stripos($bilingualStatus, 'Major') !== false) {
                                    $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                } elseif (stripos($bilingualStatus, 'Dilapidated') !== false || stripos($bilingualStatus, 'Dangerous') !== false || stripos($bilingualStatus, 'Condemn') !== false) {
                                    $statusClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                }
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusClass }}">
                                {{ $bilingualStatus }}
                            </span>
                        </td>

                        <td class="zems-table-td text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" @click="openEditModal({{ json_encode($b) }})" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Building">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button type="button" @click="openDeleteModal(@js(route('school.buildings.destroy', $b->id)), @js($b->buildingName))" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Building">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-sm font-black uppercase tracking-wider text-slate-500">No building records added yet</p>
                                <button type="button" @click="openCreateModal()" class="text-xs font-bold text-blue-600 hover:underline">
                                    + Add First Building
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Native dialog uses the browser top layer so overlays cannot intercept form clicks --}}
    <dialog x-ref="buildingDialog"
            x-effect="showFormModal ? (!$el.open && $el.showModal()) : $el.close()"
            @cancel.prevent="showFormModal = false"
            @click.self="showFormModal = false"
            aria-labelledby="building-dialog-title"
            class="m-auto w-[calc(100%-1.5rem)] max-w-lg md:max-w-2xl lg:max-w-3xl max-h-[calc(100dvh-2rem)] p-0 border-0 rounded-2xl shadow-2xl backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm">
        <div class="flex flex-col max-h-[calc(100dvh-2rem)] bg-white text-left">

            {{-- Header --}}
            <div class="flex items-center justify-between shrink-0 px-4 sm:px-6 py-4 md:px-8 md:py-5 border-b border-slate-100 bg-slate-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="building-dialog-title" class="text-base sm:text-lg md:text-xl font-black text-slate-900 leading-tight" x-text="isEdit ? 'Edit Building Record' : 'Add New Building Record'"></h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $school->name }} &bull; Facilities Registry</p>
                    </div>
                </div>
                <button type="button" @click="showFormModal = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form :action="formAction" method="POST" id="schoolBuildingForm" class="flex flex-col min-h-0">
                @csrf
                <input type="hidden" name="_building_id" :value="isEdit ? editingBuildingId : ''">
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="p-4 sm:p-6 md:p-8 space-y-5 min-h-0 overflow-y-auto overscroll-contain">
                    @php
                        $buildingErrors = isset($errors) ? $errors->getBag('building') : new \Illuminate\Support\MessageBag();
                    @endphp
                    @if($buildingErrors->any())
                    <div x-show="showValidationErrors" role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        <p class="font-bold">Please correct the following errors:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach($buildingErrors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                        {{-- Building Name (1 column on PC) --}}
                        <div class="md:col-span-1">
                            <label class="zems-input-label">Building Name / Hall <span class="text-rose-500">*</span></label>
                            <input type="text" name="buildingName" maxlength="191" x-model="formData.buildingName" required placeholder="e.g. ප්‍රධාන ශාලාව, Main Hall, Primary Block" class="zems-input h-[44px] text-xs sm:text-sm">
                        </div>

                        {{-- Building Category / Type (1 column on PC) --}}
                        <div class="md:col-span-1">
                            <label class="zems-input-label">Building Category / වර්ගය <span class="text-rose-500">*</span></label>
                            <select name="buildingType" x-model="formData.buildingType" class="zems-input h-[44px] text-xs sm:text-sm font-semibold">
                                <option value="Learning / ඉගෙනුම්">Learning / ඉගෙනුම්</option>
                                <option value="Administrative / පරිපාලන">Administrative / පරිපාලන</option>
                                <option value="Teachers Quarters / ගුරු නිවාස">Teachers Quarters / ගුරු නිවාස</option>
                                <option value="Principal Quarters / විදුහල්පති නිල නිවාස">Principal Quarters / විදුහල්පති නිල නිවාස</option>
                                <option value="Other / වෙනත්">Other / වෙනත්</option>
                            </select>
                        </div>

                        {{-- Dimensions Section (Length, Width & Calculated Area) --}}
                        <div class="md:col-span-2 bg-slate-50/80 border border-slate-200/80 rounded-xl p-3.5 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-black text-slate-700 uppercase tracking-wider">Building Dimensions & Area</span>
                                <span class="text-[10px] text-slate-400 font-semibold">Area calculates automatically from Length &times; Width</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="zems-input-label">Length (feet) / දිග (අඩි)</label>
                                    <input type="number" step="0.01" min="0" name="lengthFeet" x-model="formData.lengthFeet" @input="calculateArea()" placeholder="e.g. 80" class="zems-input h-[42px] text-xs sm:text-sm">
                                </div>
                                <div>
                                    <label class="zems-input-label">Width (feet) / පළල (අඩි)</label>
                                    <input type="number" step="0.01" min="0" name="widthFeet" x-model="formData.widthFeet" @input="calculateArea()" placeholder="e.g. 20" class="zems-input h-[42px] text-xs sm:text-sm">
                                </div>
                                <div>
                                    <label class="zems-input-label">Total Area (Sq.ft) / වර්ග ප්‍රමාණය</label>
                                    <input type="number" step="0.01" min="0" name="areaSqft" x-model="formData.areaSqft" placeholder="e.g. 1600" class="zems-input h-[42px] text-xs sm:text-sm bg-white font-bold text-slate-900">
                                </div>
                            </div>
                            <div>
                                <label class="zems-input-label">Custom Dimension String (Optional)</label>
                                <input type="text" name="size" maxlength="100" x-model="formData.size" placeholder="e.g. 80' x 20' (1,600 sqft) or 100*50 - 2 floor" class="zems-input h-[38px] text-xs text-slate-600">
                            </div>
                        </div>

                        {{-- Floors Count (1 column on PC) --}}
                        <div class="md:col-span-1">
                            <label class="zems-input-label">Floors Count / මහල් ගණන</label>
                            <input type="number" name="floors" min="1" max="20" x-model="formData.floors" @input="calculateArea()" class="zems-input h-[44px] text-xs sm:text-sm" placeholder="1">
                        </div>

                        {{-- Condition / Status (1 column on PC) --}}
                        <div class="md:col-span-1">
                            <label class="zems-input-label">Structural Condition / තත්ත්වය <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="formData.status" required class="zems-input h-[44px] text-xs sm:text-sm font-semibold">
                                <option value="Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)">Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)</option>
                                <option value="Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)">Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)</option>
                                <option value="Needs Major Renovation (ප්‍රධාන අලුත්වැඩියා අවශ්‍යයි)">Needs Major Renovation (ප්‍රධාන අලුත්වැඩියා අවශ්‍යයි)</option>
                                <option value="Dilapidated / Dangerous (ඉතා අබලන් / අවදානම්)">Dilapidated / Dangerous (ඉතා අබලන් / අවදානම්)</option>
                                <option value="Condemned / Unusable (භාවිතයෙන් ඉවත් කළ යුතු)">Condemned / Unusable (භාවිතයෙන් ඉවත් කළ යුතු)</option>
                            </select>
                        </div>

                        {{-- Year Built (1 column on PC) --}}
                        <div class="md:col-span-1">
                            <label class="zems-input-label">Year Built / ඉදිකළ වර්ෂය</label>
                            <input type="number" name="constructionYear" min="1800" max="2050" x-model="formData.constructionYear" placeholder="e.g. 2005" class="zems-input h-[44px] text-xs sm:text-sm">
                        </div>

                        {{-- Last Repair Year (1 column on PC) --}}
                        <div class="md:col-span-1">
                            <label class="zems-input-label">Last Repair Year / අවසන් අලුත්වැඩියා වර්ෂය</label>
                            <input type="number" name="lastRepairYear" min="1800" max="2050" x-model="formData.lastRepairYear" placeholder="e.g. 2018" class="zems-input h-[44px] text-xs sm:text-sm">
                            <span class="text-[10px] text-slate-400 mt-1 block">Leave empty or enter year (flags urgency if &ge; 10 yrs)</span>
                        </div>

                        {{-- Notes (Spans 2 columns on PC) --}}
                        <div class="md:col-span-2">
                            <label class="zems-input-label">Notes & Description / සටහන්</label>
                            <textarea name="notes" x-model="formData.notes" rows="3" placeholder="Specific repairs needed, funding remarks..." class="zems-input text-xs sm:text-sm py-2.5"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 shrink-0 px-4 sm:px-6 py-4 md:px-8 md:py-4 border-t border-slate-100 bg-slate-50/80">
                    <button type="button" @click="showFormModal = false" class="zems-btn-secondary w-full sm:w-auto text-xs sm:text-sm !py-2.5 px-5">Cancel</button>
                    <button type="submit" class="zems-btn-primary w-full sm:w-auto text-xs sm:text-sm !py-2.5 px-6 shadow-md" x-text="isEdit ? 'Save Changes' : 'Create Building'"></button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- Delete Confirmation Modal --}}
    <template x-teleport="body">
        <div x-show="showDeleteModal" 
             @keydown.escape.window="showDeleteModal = false"
             class="fixed inset-0 z-[70] overflow-y-auto" 
             style="display:none;">
            
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showDeleteModal = false" 
                 aria-hidden="true"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl p-6 text-center transform transition-all my-8 z-10 animate-scale-in"
                     @click.stop>
                    <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Delete Building?</h3>
                    <p class="text-sm text-slate-500 mt-2">Are you sure you want to delete <span x-text="deleteBuildingName" class="font-bold text-slate-700"></span>? This action cannot be undone.</p>
                    <form :action="deleteAction" method="POST" class="mt-6 flex flex-col-reverse sm:flex-row gap-2 sm:gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="showDeleteModal = false" class="zems-btn-secondary w-full sm:flex-1 text-xs sm:text-sm py-2.5">Cancel</button>
                        <button type="submit" class="zems-btn-primary !bg-rose-600 hover:!bg-rose-700 w-full sm:flex-1 text-xs sm:text-sm py-2.5 shadow-md">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function schoolBuildingManager() {
    const hasErrors = @js(isset($errors) && $errors->getBag('building')->any());
    const previousId = @js(old('_building_id'));
    const previousData = @js([
        'buildingName' => old('buildingName', ''),
        'buildingType' => old('buildingType', 'Learning / ඉගෙනුම්'),
        'size' => old('size', ''),
        'lengthFeet' => old('lengthFeet', ''),
        'widthFeet' => old('widthFeet', ''),
        'areaSqft' => old('areaSqft', ''),
        'floors' => old('floors', 1),
        'constructionYear' => old('constructionYear', ''),
        'lastRepairYear' => old('lastRepairYear', ''),
        'status' => old('status', 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)'),
        'notes' => old('notes', ''),
    ]);
    return {
        showFormModal: hasErrors,
        showValidationErrors: hasErrors,
        editingBuildingId: hasErrors ? previousId : null,
        isEdit: hasErrors && !!previousId,
        formAction: hasErrors && previousId ? @js(url('dashboard/school/buildings')) + '/' + encodeURIComponent(previousId) : @js(route('school.buildings.store')),
        formData: previousData,
        showDeleteModal: false,
        deleteAction: '',
        deleteBuildingName: '',

        normalizeStatus(raw) {
            if (!raw) return 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)';
            const s = ('' + raw).toLowerCase();
            if (s.includes('dilapidated') || s.includes('danger') || s.includes('condemn') || s.includes('ඉතා අබලන්') || s.includes('අයහපත්') || s.includes('පරිහරණයට නොගනී')) {
                return 'Dilapidated / Dangerous (ඉතා අබලන් / අවදානම්)';
            }
            if (s.includes('major') || s.includes('ප්‍රතිසංස්කරණ') || s.includes('අලුත්වැඩියා') || s.includes('වහල') || s.includes('දොර') || s.includes('ජනෙල්') || s.includes('කපරාරු') || s.includes('සිවිලිම') || s.includes('බිම') || s.includes('විදුලිය නොමැත') || s.includes('පේමන්ට්') || s.includes('පිළිසකර') || s.includes('වයරින්')) {
                if (s.includes('minor') || s.includes('සුළු')) {
                    return 'Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)';
                }
                return 'Needs Major Renovation (ප්‍රධාන අලුත්වැඩියා අවශ්‍යයි)';
            }
            if (s.includes('minor') || s.includes('fair') || s.includes('සුළු') || s.includes('සාමාන්‍ය') || s.includes('තීන්ත')) {
                return 'Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)';
            }
            return 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)';
        },

        calculateArea() {
            const len = parseFloat(this.formData.lengthFeet);
            const wid = parseFloat(this.formData.widthFeet);
            const fl = parseInt(this.formData.floors) || 1;
            if (!isNaN(len) && !isNaN(wid) && len > 0 && wid > 0) {
                const total = Math.round(len * wid * Math.max(1, fl) * 100) / 100;
                this.formData.areaSqft = total;
                if (!this.formData.size || this.formData.size.includes('x') || this.formData.size.includes('sqft')) {
                    this.formData.size = `${len}' x ${wid}' (${total} sqft)`;
                }
            }
        },

        openCreateModal() {
            this.showValidationErrors = false;
            this.editingBuildingId = null;
            this.isEdit = false;
            this.formAction = '{{ route("school.buildings.store") }}';
            this.formData = {
                buildingName: '',
                buildingType: 'Learning / ඉගෙනුම්',
                size: '',
                lengthFeet: '',
                widthFeet: '',
                areaSqft: '',
                floors: 1,
                constructionYear: '',
                lastRepairYear: '',
                status: 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)',
                notes: ''
            };
            this.showFormModal = true;
        },

        openEditModal(building) {
            this.showValidationErrors = false;
            this.editingBuildingId = building.id;
            this.isEdit = true;
            this.formAction = '{{ url("dashboard/school/buildings") }}/' + building.id;
            this.formData = {
                buildingName: building.buildingName,
                buildingType: building.buildingType || 'Learning / ඉගෙනුම්',
                size: building.size || '',
                lengthFeet: building.lengthFeet ?? '',
                widthFeet: building.widthFeet ?? '',
                areaSqft: building.areaSqft ?? '',
                floors: building.floors || 1,
                constructionYear: building.constructionYear || '',
                lastRepairYear: building.lastRepairYear || '',
                status: this.normalizeStatus(building.bilingual_status || building.status),
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

// Global window helpers so click handlers can also be called directly
window.openCreateSchoolBuildingModal = function() {
    const el = document.querySelector('[x-data="schoolBuildingManager()"]');
    if (el && window.Alpine) {
        Alpine.$data(el).openCreateModal();
    }
};
</script>
@endsection
