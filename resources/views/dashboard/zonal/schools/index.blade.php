@extends('layouts.dashboard')
@section('title', 'Manage Schools')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="confirmDelete">
    {{-- Gradient Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Schools Management</h1>
                <p class="zems-header-subtitle">Create and manage schools across the educational zone</p>
            </div>
        </div>
        <button onclick="document.getElementById('addSchoolModal').style.display='flex'"
                class="zems-btn-primary !bg-white !text-blue-700 hover:!bg-blue-50">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add School
        </button>
    </div>

    {{-- Filters Bar --}}
    <div class="zems-card-base p-5">
        <div class="flex flex-col md:flex-row gap-4">
            {{-- Search --}}
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchInput"
                       placeholder="Search by name, census no, or address..."
                       class="zems-input pl-12 h-[46px]">
            </div>

            {{-- School Type Filter --}}
            <div class="w-full md:w-48">
                <select id="schoolTypeFilter"
                        class="zems-input h-[46px]">
                    <option value="">All Types</option>
                    <option value="1AB">Type 1AB (A/L All Streams)</option>
                    <option value="1C">Type 1C (A/L Arts & Commerce)</option>
                    <option value="Type II">Type II (Junior 1-11)</option>
                    <option value="Type III">Type III (Primary 1-5)</option>
                </select>
            </div>

            {{-- Division Filter --}}
            <div class="w-full md:w-48">
                <select id="divisionFilter"
                        class="zems-input h-[46px]">
                    <option value="">All Divisions</option>
                    <option value="Wilgamuwa">Wilgamuwa</option>
                    <option value="Laggala">Laggala</option>
                </select>
            </div>

            {{-- Clear Button (hidden by default, shown by JS) --}}
            <button id="clearFiltersBtn" style="display:none;" class="zems-btn-secondary h-[46px] uppercase tracking-wide text-xs px-4">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Clear
            </button>
        </div>

        {{-- Results Count --}}
        <div class="mt-3 flex items-center justify-between text-sm">
            <span class="text-slate-500 font-medium">
                Showing <span id="filteredCount" class="font-bold text-slate-900">{{ $totalSchools }}</span> of {{ $totalSchools }} schools
            </span>
            <span id="filtersActiveLabel" style="display:none;" class="text-blue-600 font-bold uppercase tracking-wider text-[10px] items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filters active
            </span>
        </div>
    </div>

    {{-- Schools Grid --}}
    <div id="schoolsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($schools as $school)
        <div class="school-card zems-card-base p-6 flex flex-col justify-between hover:border-blue-300 hover:shadow-lg transition-all group h-full"
             data-name="{{ strtolower($school->name) }}"
             data-census="{{ strtolower($school->censusNo ?? '') }}"
             data-address="{{ strtolower($school->address ?? '') }}"
             data-type="{{ $school->schoolType ?? '' }}"
             data-division="{{ $school->eduDivision ?? '' }}">
            <div>
                <div class="flex justify-between items-start mb-4">
                    {{-- Avatar --}}
                    <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-xl border border-indigo-100 shadow-sm">
                        {{ strtoupper(substr($school->name, 0, 1)) }}
                    </div>
                    
                    {{-- Actions --}}
                    <div class="flex gap-1">
                        <a href="{{ route('zonal.schools.show', $school->id) }}"
                           class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                           title="View Profile">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <button onclick="openEditModal({{ json_encode($school) }})"
                                class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="Edit School">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button @click="open('{{ route('zonal.schools.destroy', $school->id) }}', '{{ addslashes($school->name) }}')"
                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Remove School">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <h3 class="font-bold text-slate-900 text-lg leading-tight mb-2 min-h-[3rem]">{{ $school->name }}</h3>
                
                <div class="flex items-center flex-wrap gap-2 text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-3">
                    @if($school->censusNo)<span>CENSUS: {{ $school->censusNo }}</span> <span class="text-slate-300">|</span> @endif
                    <span>{{ $school->schoolType ?? 'PRIMARY' }}</span>
                    @if($school->dsDivision)
                    <span class="text-slate-300">|</span>
                    <span class="text-indigo-600 font-semibold">{{ $school->dsDivision }}</span>
                    @endif
                </div>

                @if($school->difficultyCategory)
                <div class="mb-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[11px] font-bold border {{ $school->difficulty_badge_class }}">
                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ $school->bilingual_difficulty }}
                    </span>
                </div>
                @endif
                
                <div class="flex items-start gap-2 text-slate-500 text-sm mb-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="line-clamp-1 italic">{{ $school->address ?? 'No address' }}</span>
                </div>

                @if($school->landArea || $school->gnDivision)
                <div class="flex items-center flex-wrap gap-2 text-xs text-slate-500 mb-2">
                    @if($school->landArea)
                    <span class="inline-flex items-center gap-1 font-medium bg-slate-50 px-2 py-0.5 rounded border border-slate-200" title="School Land Area">
                        <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        {{ $school->landArea }}
                    </span>
                    @endif
                    @if($school->gnDivision)
                    <span class="inline-flex items-center gap-1 font-medium bg-slate-50 px-2 py-0.5 rounded border border-slate-200" title="Grama Niladhari Division">
                        GND: {{ $school->gnDivision }}
                    </span>
                    @endif
                </div>
                @endif
            </div>

            <div class="flex justify-between items-center text-xs font-bold mt-6 pt-4 border-t border-slate-100">
                <span class="text-slate-400 uppercase tracking-wider">ACADEMIC STAFF</span>
                <span class="text-indigo-600">{{ $school->teachers_count }} Members</span>
            </div>
        </div>
        @empty
        <div id="emptyState" class="col-span-full text-center py-16 text-slate-400 zems-card-base border-2 border-dashed border-slate-200">
            <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <p class="text-lg font-bold">No schools found.</p>
            <p class="text-sm">Click "Add School" to register your first school.</p>
        </div>
        @endforelse
        {{-- No results state (shown by JS when filters match nothing) --}}
        <div id="noResultsState" class="col-span-full text-center py-16 text-slate-400 zems-card-base border-2 border-dashed border-slate-200" style="display: none;">
            <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <p class="text-lg font-bold">No schools match your filters.</p>
            <p class="text-sm">Try adjusting your search or filter criteria.</p>
        </div>
    </div>

    {{-- Add School Modal --}}
    <div id="addSchoolModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)this.style.display='none'">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10"></div>
        <form method="POST" action="{{ route('zonal.schools.store') }}" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col relative z-20 overflow-hidden animate-scale-in">
            @csrf
            {{-- Header --}}
            <div class="flex justify-between items-center px-6 py-4 bg-slate-50 border-b border-slate-200 shrink-0">
                <h3 class="font-bold text-xl text-slate-900">Add New School</h3>
                <button type="button" onclick="document.getElementById('addSchoolModal').style.display='none'" class="p-1.5 hover:bg-slate-200 rounded-full transition-colors text-slate-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 overflow-y-auto flex flex-col gap-5">
                {{-- School Name --}}
                <div>
                    <label class="zems-input-label">School Name</label>
                    <input type="text" name="name" required class="zems-input">
                </div>

                {{-- CENSUS No + School Type --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="zems-input-label">CENSUS No</label>
                        <input type="text" name="censusNo" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">School Type (Classification)</label>
                        <select name="schoolType" id="createSchoolType" onchange="updateGradeRange('create')" class="zems-input cursor-pointer">
                            <option value="">Select Type</option>
                            <optgroup label="Senior Secondary Schools (A/L)">
                                <option value="1AB">Type 1AB - All Streams</option>
                                <option value="1C">Type 1C - Arts & Commerce Only</option>
                            </optgroup>
                            <optgroup label="Junior Schools">
                                <option value="Type II">Type II - Junior School (Grade 1-11)</option>
                            </optgroup>
                            <optgroup label="Primary Schools">
                                <option value="Type III">Type III - Primary School (Grade 1-5)</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                {{-- Grade Range --}}
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <label class="block text-sm font-bold text-emerald-800 mb-3">Grade Range</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Starting Grade</label>
                            <select name="gradeStart" id="createGradeStart" onchange="updateInfoText('create')" class="zems-input cursor-pointer">
                                @for($g = 1; $g <= 13; $g++)
                                <option value="{{ $g }}">Grade {{ $g }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Ending Grade</label>
                            <select name="gradeEnd" id="createGradeEnd" onchange="updateInfoText('create')" class="zems-input cursor-pointer">
                                @for($g = 1; $g <= 13; $g++)
                                <option value="{{ $g }}" {{ $g == 13 ? 'selected' : '' }}>Grade {{ $g }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <p id="createInfoText" class="text-xs text-emerald-600 mt-2">
                        This school will show grades from <strong>Grade 1</strong> to <strong>Grade 13</strong>
                    </p>
                </div>

                {{-- Address & Land Area --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="zems-input-label">Address / ලිපිනය</label>
                        <input type="text" name="address" placeholder="e.g. Main Street, Hettipola" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">School Land Area / ඉඩම් ප්‍රමාණය</label>
                        <input type="text" name="landArea" placeholder="e.g. 2 Acres 1 Rood 15 Perches" class="zems-input">
                    </div>
                </div>

                {{-- School Difficulty & Education Division --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="zems-input-label">School Difficulty Category / දුෂ්කරතා වර්ගීකරණය</label>
                        <select name="difficultyCategory" class="zems-input cursor-pointer font-semibold">
                            <option value="">Select Difficulty Category</option>
                            <option value="Very Convenient">Very Convenient (ඉතා පහසු)</option>
                            <option value="Convenient">Convenient (පහසු)</option>
                            <option value="Difficult">Difficult (දුෂ්කර)</option>
                            <option value="Very Difficult">Very Difficult (ඉතා දුෂ්කර)</option>
                        </select>
                    </div>
                    <div>
                        <label class="zems-input-label">Education Division / අධ්‍යාපන කොට්ඨාසය</label>
                        <select name="eduDivision" class="zems-input cursor-pointer">
                            <option value="">Select Division</option>
                            <option value="Wilgamuwa">Wilgamuwa (විල්ගමුව)</option>
                            <option value="Laggala">Laggala (ලග්ගල)</option>
                        </select>
                    </div>
                </div>

                {{-- DS Division & Grama Niladhari Division --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="zems-input-label">Divisional Secretariat (DSD) / ප්‍රාදේශීය ලේකම් කොට්ඨාසය</label>
                        <select name="dsDivision" class="zems-input cursor-pointer font-semibold">
                            <option value="">Select DS Division</option>
                            <option value="Wilgamuwa">Wilgamuwa (විල්ගමුව)</option>
                            <option value="Laggala-Pallegama">Laggala-Pallegama (ලග්ගල පල්ලේගම)</option>
                            <option value="Naula">Naula (නාවුල)</option>
                            <option value="Rattota">Rattota (රත්තොට)</option>
                            <option value="Ambanganga Korale">Ambanganga Korale (අඹන්ගඟ කෝරළේ)</option>
                        </select>
                    </div>
                    <div>
                        <label class="zems-input-label">Grama Niladhari Division (GND) / ග්‍රාම නිලධාරී වසම</label>
                        <select name="gnDivision" class="zems-input cursor-pointer">
                            <option value="">Select GN Division</option>
                            <optgroup label="Wilgamuwa DS Division (39 GN Divisions)">
                                <option value="80 Yaya">80 Yaya (E393D)</option>
                                <option value="Aliyawala">Aliyawala (E395L)</option>
                                <option value="Aliwanguwa">Aliwanguwa (E398G)</option>
                                <option value="Bathgampola">Bathgampola (E395D)</option>
                                <option value="Dewagiriya">Dewagiriya (E394F)</option>
                                <option value="Dunuvilapitiya">Dunuvilapitiya (E398F)</option>
                                <option value="Gemburuoya">Gemburuoya (E395K)</option>
                                <option value="Guruwelayaya">Guruwelayaya (E394D)</option>
                                <option value="Handungamuwa">Handungamuwa (E398)</option>
                                <option value="Hettipola">Hettipola (E394A)</option>
                                <option value="Karawgahawewa">Karawgahawewa (E395B)</option>
                                <option value="Kumbukandana">Kumbukandana (E398B)</option>
                                <option value="Kumbukoya">Kumbukoya (E398I)</option>
                                <option value="Lediyangala">Lediyangala (E395A)</option>
                                <option value="Malgammana">Malgammana (E395J)</option>
                                <option value="Maraka">Maraka (E395H)</option>
                                <option value="Medakanda">Medakanda (E398A)</option>
                                <option value="Mewaobey">Mewaobey (E393B)</option>
                                <option value="Moragahaulpatha">Moragahaulpatha (E395F)</option>
                                <option value="Nagolla">Nagolla (E395E)</option>
                                <option value="Naminigama">Naminigama (E393B)</option>
                                <option value="Naminioya">Naminioya (E394C)</option>
                                <option value="Nugagolla">Nugagolla (E393A)</option>
                                <option value="Palupitiya">Palupitiya (E398H)</option>
                                <option value="Perakanaththa">Perakanaththa (E395C)</option>
                                <option value="Piduruwalla">Piduruwalla (E394E)</option>
                                <option value="Radunnewewa">Radunnewewa (E395I)</option>
                                <option value="Rattotayaya">Rattotayaya (E398E)</option>
                                <option value="Sonuththa">Sonuththa (E393)</option>
                                <option value="Sulugune">Sulugune (E398L)</option>
                                <option value="Thunhiriyawewa">Thunhiriyawewa (E398C)</option>
                                <option value="Topwalapitiya">Topwalapitiya (E398J)</option>
                                <option value="Uduwelwala">Uduwelwala (E393C)</option>
                                <option value="Veheragala">Veheragala (E398D)</option>
                                <option value="Viharagama">Viharagama (E398K)</option>
                                <option value="Wanarawa">Wanarawa (E395G)</option>
                                <option value="Wilgamuwa">Wilgamuwa (E395)</option>
                            </optgroup>
                            <optgroup label="Laggala-Pallegama DS Division (37 GN Divisions)">
                                <option value="Akarahadiya">Akarahadiya (E396B)</option>
                                <option value="Atanwala">Atanwala</option>
                                <option value="Dammanthenna">Dammanthenna</option>
                                <option value="Dasgiriya">Dasgiriya</option>
                                <option value="Dewaladeniya">Dewaladeniya</option>
                                <option value="Diwulgaspathana">Diwulgaspathana</option>
                                <option value="Galgedawala">Galgedawala (E396A)</option>
                                <option value="Gangahenwala">Gangahenwala</option>
                                <option value="Gangala Puwakpitiya">Gangala Puwakpitiya</option>
                                <option value="Gonawala">Gonawala (E397B)</option>
                                <option value="Guruwela">Guruwela (E397E)</option>
                                <option value="Halminiya">Halminiya</option>
                                <option value="Haththotaamuna">Haththotaamuna</option>
                                <option value="Himbiliyakada">Himbiliyakada (E396C)</option>
                                <option value="Ilukkumbura">Ilukkumbura</option>
                                <option value="Imaduwa">Imaduwa</option>
                                <option value="Kahagala">Kahagala</option>
                                <option value="Kaluganga">Kaluganga</option>
                                <option value="Kandepitawala">Kandepitawala</option>
                                <option value="Karandamulla">Karandamulla (E397C)</option>
                                <option value="Kiyulewadiya">Kiyulewadiya</option>
                                <option value="Laggala Pallegama">Laggala Pallegama (E397)</option>
                                <option value="Lakegala">Lakegala</option>
                                <option value="Leloya">Leloya (E396)</option>
                                <option value="Mada Ela">Mada Ela</option>
                                <option value="Mahalakotuwa">Mahalakotuwa</option>
                                <option value="Maoya">Maoya</option>
                                <option value="Miniranketiya">Miniranketiya (E397D)</option>
                                <option value="Morathenna">Morathenna</option>
                                <option value="Narangamuwa">Narangamuwa</option>
                                <option value="Pitawala">Pitawala</option>
                                <option value="Poththatawela">Poththatawela</option>
                                <option value="Rambukoluwa">Rambukoluwa</option>
                                <option value="Ranamure">Ranamure</option>
                                <option value="Rathninda">Rathninda</option>
                                <option value="Rawanagama">Rawanagama (E397A)</option>
                                <option value="Waliwaranagolla">Waliwaranagolla</option>
                                <option value="Wallewala">Wallewala</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                {{-- Geographic Coordinates (Latitude & Longitude) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="zems-input-label">Latitude / අක්ෂාංශය</label>
                        <input type="number" step="any" name="latitude" placeholder="e.g. 7.7123456" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">Longitude / දේශාංශය</label>
                        <input type="number" step="any" name="longitude" placeholder="e.g. 80.9123456" class="zems-input">
                    </div>
                </div>

                {{-- Education Zone --}}
                <div>
                    <label class="zems-input-label">Education Zone / අධ්‍යාපන කලාපය</label>
                    <input type="text" name="eduZone" value="Wilgamuwa" class="zems-input">
                </div>

                {{-- Contact & Principal Information section --}}
                <div class="pt-4 border-t border-slate-200 mt-2">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-4 tracking-wide">Contact & Principal Information</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- School Email --}}
                        <div>
                            <label class="zems-input-label">School Email</label>
                            <input type="email" name="email" class="zems-input">
                        </div>
                        {{-- School Phone --}}
                        <div>
                            <label class="zems-input-label">School Phone</label>
                            <input type="text" name="contactNumber" class="zems-input">
                        </div>
                        {{-- Principal's Name --}}
                        <div>
                            <label class="zems-input-label">Principal's Name</label>
                            <input type="text" name="principalName" id="createPrincipalName" class="zems-input">
                        </div>
                        {{-- Principal's NIC --}}
                        <div class="relative">
                            <label class="zems-input-label">Principal's NIC</label>
                            <input type="text" name="principalNic" id="createPrincipalNic" autocomplete="off" class="zems-input">
                            <div id="createNicSuggestions" class="hidden absolute z-10 w-full bg-white border border-slate-200 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto"></div>
                        </div>
                        {{-- Principal's Mobile --}}
                        <div>
                            <label class="zems-input-label">Principal's Mobile</label>
                            <input type="text" name="principalMobile" id="createPrincipalMobile" class="zems-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="document.getElementById('addSchoolModal').style.display='none'" class="px-5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="zems-btn-primary py-2 px-6 shadow-md shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create School
                </button>
            </div>
        </form>
    </div>

    {{-- Edit School Modal --}}
    <div id="editSchoolModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10" onclick="document.getElementById('editSchoolModal').classList.add('hidden')"></div>
        <form id="editSchoolForm" method="POST" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-scale-in max-h-[90vh] flex flex-col relative z-20">
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
                        <label class="zems-input-label">School Name</label>
                        <input type="text" name="name" id="editName" required class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">CENSUS No</label>
                        <input type="text" name="censusNo" id="editCensusNo" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">School Type (Classification)</label>
                        <select name="schoolType" id="editSchoolType" onchange="updateGradeRange('edit')" class="zems-input cursor-pointer">
                            <option value="">Select Type</option>
                            <optgroup label="Senior Secondary Schools (A/L)">
                                <option value="1AB">Type 1AB - All Streams</option>
                                <option value="1C">Type 1C - Arts & Commerce Only</option>
                            </optgroup>
                            <optgroup label="Junior Schools">
                                <option value="Type II">Type II - Junior School (Grade 1-11)</option>
                            </optgroup>
                            <optgroup label="Primary Schools">
                                <option value="Type III">Type III - Primary School (Grade 1-5)</option>
                            </optgroup>
                        </select>
                    </div>

                    {{-- Grade Range --}}
                    <div class="md:col-span-2 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                        <label class="block text-sm font-bold text-emerald-800 mb-3">Grade Range</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Starting Grade</label>
                                <select name="gradeStart" id="editGradeStart" onchange="updateInfoText('edit')" class="zems-input cursor-pointer">
                                    @for($g = 1; $g <= 13; $g++)
                                    <option value="{{ $g }}">Grade {{ $g }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Ending Grade</label>
                                <select name="gradeEnd" id="editGradeEnd" onchange="updateInfoText('edit')" class="zems-input cursor-pointer">
                                    @for($g = 1; $g <= 13; $g++)
                                    <option value="{{ $g }}">Grade {{ $g }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <p id="editInfoText" class="text-xs text-emerald-600 mt-2">
                            This school will show grades from <strong>Grade 1</strong> to <strong>Grade 13</strong>
                        </p>
                    </div>

                    {{-- Address & Land Area --}}
                    <div>
                        <label class="zems-input-label">Address / ලිපිනය</label>
                        <input type="text" name="address" id="editAddress" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">School Land Area / ඉඩම් ප්‍රමාණය</label>
                        <input type="text" name="landArea" id="editLandArea" placeholder="e.g. 2 Acres 1 Rood 15 Perches" class="zems-input">
                    </div>

                    {{-- School Difficulty & Education Division --}}
                    <div>
                        <label class="zems-input-label">School Difficulty Category / දුෂ්කරතා වර්ගීකරණය</label>
                        <select name="difficultyCategory" id="editDifficultyCategory" class="zems-input cursor-pointer font-semibold">
                            <option value="">Select Difficulty Category</option>
                            <option value="Very Convenient">Very Convenient (ඉතා පහසු)</option>
                            <option value="Convenient">Convenient (පහසු)</option>
                            <option value="Difficult">Difficult (දුෂ්කර)</option>
                            <option value="Very Difficult">Very Difficult (ඉතා දුෂ්කර)</option>
                        </select>
                    </div>
                    <div>
                        <label class="zems-input-label">Education Division / අධ්‍යාපන කොට්ඨාසය</label>
                        <select name="eduDivision" id="editEduDivision" class="zems-input cursor-pointer">
                            <option value="">Select Division</option>
                            <option value="Wilgamuwa">Wilgamuwa (විල්ගමුව)</option>
                            <option value="Laggala">Laggala (ලග්ගල)</option>
                        </select>
                    </div>

                    {{-- DS Division & Grama Niladhari Division --}}
                    <div>
                        <label class="zems-input-label">Divisional Secretariat (DSD) / ප්‍රාදේශීය ලේකම් කොට්ඨාසය</label>
                        <select name="dsDivision" id="editDsDivision" class="zems-input cursor-pointer font-semibold">
                            <option value="">Select DS Division</option>
                            <option value="Wilgamuwa">Wilgamuwa (විල්ගමුව)</option>
                            <option value="Laggala-Pallegama">Laggala-Pallegama (ලග්ගල පල්ලේගම)</option>
                            <option value="Naula">Naula (නාවුල)</option>
                            <option value="Rattota">Rattota (රත්තොට)</option>
                            <option value="Ambanganga Korale">Ambanganga Korale (අඹන්ගඟ කෝරළේ)</option>
                        </select>
                    </div>
                    <div>
                        <label class="zems-input-label">Grama Niladhari Division (GND) / ග්‍රාම නිලධාරී වසම</label>
                        <select name="gnDivision" id="editGnDivision" class="zems-input cursor-pointer">
                            <option value="">Select GN Division</option>
                            <optgroup label="Wilgamuwa DS Division (39 GN Divisions)">
                                <option value="80 Yaya">80 Yaya (E393D)</option>
                                <option value="Aliyawala">Aliyawala (E395L)</option>
                                <option value="Aliwanguwa">Aliwanguwa (E398G)</option>
                                <option value="Bathgampola">Bathgampola (E395D)</option>
                                <option value="Dewagiriya">Dewagiriya (E394F)</option>
                                <option value="Dunuvilapitiya">Dunuvilapitiya (E398F)</option>
                                <option value="Gemburuoya">Gemburuoya (E395K)</option>
                                <option value="Guruwelayaya">Guruwelayaya (E394D)</option>
                                <option value="Handungamuwa">Handungamuwa (E398)</option>
                                <option value="Hettipola">Hettipola (E394A)</option>
                                <option value="Karawgahawewa">Karawgahawewa (E395B)</option>
                                <option value="Kumbukandana">Kumbukandana (E398B)</option>
                                <option value="Kumbukoya">Kumbukoya (E398I)</option>
                                <option value="Lediyangala">Lediyangala (E395A)</option>
                                <option value="Malgammana">Malgammana (E395J)</option>
                                <option value="Maraka">Maraka (E395H)</option>
                                <option value="Medakanda">Medakanda (E398A)</option>
                                <option value="Mewaobey">Mewaobey (E393B)</option>
                                <option value="Moragahaulpatha">Moragahaulpatha (E395F)</option>
                                <option value="Nagolla">Nagolla (E395E)</option>
                                <option value="Naminigama">Naminigama (E393B)</option>
                                <option value="Naminioya">Naminioya (E394C)</option>
                                <option value="Nugagolla">Nugagolla (E393A)</option>
                                <option value="Palupitiya">Palupitiya (E398H)</option>
                                <option value="Perakanaththa">Perakanaththa (E395C)</option>
                                <option value="Piduruwalla">Piduruwalla (E394E)</option>
                                <option value="Radunnewewa">Radunnewewa (E395I)</option>
                                <option value="Rattotayaya">Rattotayaya (E398E)</option>
                                <option value="Sonuththa">Sonuththa (E393)</option>
                                <option value="Sulugune">Sulugune (E398L)</option>
                                <option value="Thunhiriyawewa">Thunhiriyawewa (E398C)</option>
                                <option value="Topwalapitiya">Topwalapitiya (E398J)</option>
                                <option value="Uduwelwala">Uduwelwala (E393C)</option>
                                <option value="Veheragala">Veheragala (E398D)</option>
                                <option value="Viharagama">Viharagama (E398K)</option>
                                <option value="Wanarawa">Wanarawa (E395G)</option>
                                <option value="Wilgamuwa">Wilgamuwa (E395)</option>
                            </optgroup>
                            <optgroup label="Laggala-Pallegama DS Division (37 GN Divisions)">
                                <option value="Akarahadiya">Akarahadiya (E396B)</option>
                                <option value="Atanwala">Atanwala</option>
                                <option value="Dammanthenna">Dammanthenna</option>
                                <option value="Dasgiriya">Dasgiriya</option>
                                <option value="Dewaladeniya">Dewaladeniya</option>
                                <option value="Diwulgaspathana">Diwulgaspathana</option>
                                <option value="Galgedawala">Galgedawala (E396A)</option>
                                <option value="Gangahenwala">Gangahenwala</option>
                                <option value="Gangala Puwakpitiya">Gangala Puwakpitiya</option>
                                <option value="Gonawala">Gonawala (E397B)</option>
                                <option value="Guruwela">Guruwela (E397E)</option>
                                <option value="Halminiya">Halminiya</option>
                                <option value="Haththotaamuna">Haththotaamuna</option>
                                <option value="Himbiliyakada">Himbiliyakada (E396C)</option>
                                <option value="Ilukkumbura">Ilukkumbura</option>
                                <option value="Imaduwa">Imaduwa</option>
                                <option value="Kahagala">Kahagala</option>
                                <option value="Kaluganga">Kaluganga</option>
                                <option value="Kandepitawala">Kandepitawala</option>
                                <option value="Karandamulla">Karandamulla (E397C)</option>
                                <option value="Kiyulewadiya">Kiyulewadiya</option>
                                <option value="Laggala Pallegama">Laggala Pallegama (E397)</option>
                                <option value="Lakegala">Lakegala</option>
                                <option value="Leloya">Leloya (E396)</option>
                                <option value="Mada Ela">Mada Ela</option>
                                <option value="Mahalakotuwa">Mahalakotuwa</option>
                                <option value="Maoya">Maoya</option>
                                <option value="Miniranketiya">Miniranketiya (E397D)</option>
                                <option value="Morathenna">Morathenna</option>
                                <option value="Narangamuwa">Narangamuwa</option>
                                <option value="Pitawala">Pitawala</option>
                                <option value="Poththatawela">Poththatawela</option>
                                <option value="Rambukoluwa">Rambukoluwa</option>
                                <option value="Ranamure">Ranamure</option>
                                <option value="Rathninda">Rathninda</option>
                                <option value="Rawanagama">Rawanagama (E397A)</option>
                                <option value="Waliwaranagolla">Waliwaranagolla</option>
                                <option value="Wallewala">Wallewala</option>
                            </optgroup>
                        </select>
                    </div>

                    {{-- Geographic Coordinates (Latitude & Longitude) --}}
                    <div>
                        <label class="zems-input-label">Latitude / අක්ෂාංශය</label>
                        <input type="number" step="any" name="latitude" id="editLatitude" placeholder="e.g. 7.7123456" class="zems-input">
                    </div>
                    <div>
                        <label class="zems-input-label">Longitude / දේශාංශය</label>
                        <input type="number" step="any" name="longitude" id="editLongitude" placeholder="e.g. 80.9123456" class="zems-input">
                    </div>

                    <div class="md:col-span-2">
                        <label class="zems-input-label">Education Zone / අධ්‍යාපන කලාපය</label>
                        <input type="text" name="eduZone" id="editEduZone" class="zems-input">
                    </div>

                    <div class="md:col-span-2 pt-4 border-t border-slate-200 mt-2">
                        <p class="text-xs font-bold text-blue-600 uppercase mb-4 tracking-wide">Contact & Principal Information</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="zems-input-label">School Email</label>
                                <input type="email" name="email" id="editEmail" class="zems-input">
                            </div>
                            <div>
                                <label class="zems-input-label">School Phone</label>
                                <input type="text" name="contactNumber" id="editContactNumber" class="zems-input">
                            </div>
                            <div>
                                <label class="zems-input-label">Principal's Name</label>
                                <input type="text" name="principalName" id="editPrincipalName" class="zems-input">
                            </div>
                            <div class="relative">
                                <label class="zems-input-label">Principal's NIC</label>
                                <input type="text" name="principalNic" id="editPrincipalNic" autocomplete="off" class="zems-input">
                                <div id="editNicSuggestions" class="hidden absolute z-10 w-full bg-white border border-slate-200 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto"></div>
                            </div>
                            <div>
                                <label class="zems-input-label">Principal's Mobile</label>
                                <input type="text" name="principalMobile" id="editPrincipalMobile" class="zems-input">
                            </div>
                            <div>
                                <label class="zems-input-label">Sanctioned Positions</label>
                                <input type="number" name="sanctionedPositions" id="editSanctionedPositions" min="0" class="zems-input">
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

    {{-- Delete Confirmation Modal --}}
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-scale-in text-center relative z-20">
            <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Delete School?</h3>
            <p class="text-sm text-slate-500 mt-2">Are you sure you want to delete <span x-text="itemName" class="font-bold text-slate-700"></span>?</p>
            <div class="flex gap-3 mt-6">
                <button @click="close" class="zems-btn-secondary flex-1 text-slate-600">Cancel</button>
                <button @click="submit" class="zems-btn-primary !bg-red-600 hover:!bg-red-700 flex-1">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    const teachers = @json($teachers);

    function setupNicAutocomplete(inputId, suggestionsId, prefix) {
        const input = document.getElementById(inputId);
        const suggestions = document.getElementById(suggestionsId);
        
        if (!input || !suggestions) return;

        input.addEventListener('input', function() {
            const val = this.value;
            if (val.length < 3) {
                suggestions.classList.add('hidden');
                return;
            }
            const matches = teachers.filter(t => (t.nic || '').toLowerCase().includes(val.toLowerCase())).slice(0, 5);
            if (matches.length > 0) {
                suggestions.innerHTML = matches.map(t => `
                    <div class="px-3 py-2 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0" 
                         onclick="fillPrincipal('${prefix}', '${t.nic}', '${(t.nameWithInitials || '').replace(/'/g, "\\'")}', '${t.phone || ''}')">
                        <div class="font-bold text-slate-800 text-sm">${t.nic}</div>
                        <div class="text-xs text-slate-500">${t.nameWithInitials}</div>
                    </div>
                `).join('');
                suggestions.classList.remove('hidden');
            } else {
                suggestions.classList.add('hidden');
            }
        });

        // Hide on click outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add('hidden');
            }
        });
    }

    function fillPrincipal(prefix, nic, name, phone) {
        document.getElementById(prefix + 'PrincipalNic').value = nic;
        document.getElementById(prefix + 'PrincipalName').value = name;
        document.getElementById(prefix + 'PrincipalMobile').value = phone || '';
        document.getElementById(prefix + 'NicSuggestions').classList.add('hidden');
    }

    function openEditModal(school) {
        const form = document.getElementById('editSchoolForm');
        form.action = '/dashboard/zonal/schools/' + school.id;

        document.getElementById('editName').value = school.name || '';
        document.getElementById('editCensusNo').value = school.censusNo || '';
        document.getElementById('editSchoolType').value = school.schoolType || '';
        document.getElementById('editGradeStart').value = school.gradeStart || 1;
        document.getElementById('editGradeEnd').value = school.gradeEnd || 13;
        document.getElementById('editAddress').value = school.address || '';
        document.getElementById('editEduZone').value = school.eduZone || 'Wilgamuwa';
        document.getElementById('editEduDivision').value = school.eduDivision || '';
        document.getElementById('editEmail').value = school.email || '';
        document.getElementById('editContactNumber').value = school.contactNumber || '';
        document.getElementById('editPrincipalName').value = school.principalName || '';
        document.getElementById('editPrincipalNic').value = school.principalNic || '';
        document.getElementById('editPrincipalMobile').value = school.principalMobile || '';
        document.getElementById('editSanctionedPositions').value = school.sanctionedPositions || '';
        document.getElementById('editLandArea').value = school.landArea || '';
        document.getElementById('editDifficultyCategory').value = school.difficultyCategory || '';
        document.getElementById('editDsDivision').value = school.dsDivision || '';
        document.getElementById('editGnDivision').value = school.gnDivision || '';
        document.getElementById('editLatitude').value = school.latitude || '';
        document.getElementById('editLongitude').value = school.longitude || '';

        updateInfoText('edit');
        document.getElementById('editSchoolModal').classList.remove('hidden');
    }

    function updateGradeRange(prefix) {
        const type = document.getElementById(prefix + 'SchoolType').value;
        const gradeStart = document.getElementById(prefix + 'GradeStart');
        const gradeEnd = document.getElementById(prefix + 'GradeEnd');

        if (type === '1AB' || type === '1C') {
            gradeEnd.value = 13;
        } else if (type === 'Type II') {
            gradeStart.value = 1;
            gradeEnd.value = 11;
        } else if (type === 'Type III') {
            gradeStart.value = 1;
            gradeEnd.value = 5;
        }
        updateInfoText(prefix);
    }

    function updateInfoText(prefix) {
        const start = document.getElementById(prefix + 'GradeStart').value;
        const end = document.getElementById(prefix + 'GradeEnd').value;
        const textElement = document.getElementById(prefix + 'InfoText');
        if(textElement) {
             textElement.innerHTML = `This school will show grades from <strong>Grade ${start}</strong> to <strong>Grade ${end}</strong>`;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        setupNicAutocomplete('createPrincipalNic', 'createNicSuggestions', 'create');
        setupNicAutocomplete('editPrincipalNic', 'editNicSuggestions', 'edit');

        // --- Client-side filtering (matches Next.js useMemo approach) ---
        const searchInput = document.getElementById('searchInput');
        const schoolTypeFilter = document.getElementById('schoolTypeFilter');
        const divisionFilter = document.getElementById('divisionFilter');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const filteredCount = document.getElementById('filteredCount');
        const filtersActiveLabel = document.getElementById('filtersActiveLabel');
        const schoolCards = document.querySelectorAll('.school-card');
        const noResultsState = document.getElementById('noResultsState');

        function filterSchools() {
            const search = (searchInput.value || '').toLowerCase();
            const type = schoolTypeFilter.value;
            const division = divisionFilter.value;

            const hasActiveFilters = search !== '' || type !== '' || division !== '';

            let visibleCount = 0;

            schoolCards.forEach(card => {
                const name = card.dataset.name || '';
                const census = card.dataset.census || '';
                const address = card.dataset.address || '';
                const cardType = card.dataset.type || '';
                const cardDivision = card.dataset.division || '';

                const matchesSearch = search === '' ||
                    name.includes(search) ||
                    census.includes(search) ||
                    address.includes(search);

                const matchesType = type === '' || cardType === type;
                const matchesDivision = division === '' || cardDivision === division;

                if (matchesSearch && matchesType && matchesDivision) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update count
            filteredCount.textContent = visibleCount;

            // Show/hide clear button and filters label
            if (hasActiveFilters) {
                clearFiltersBtn.style.display = 'flex';
                filtersActiveLabel.style.display = 'flex';
            } else {
                clearFiltersBtn.style.display = 'none';
                filtersActiveLabel.style.display = 'none';
            }

            // Show/hide no-results state
            if (noResultsState) {
                noResultsState.style.display = (visibleCount === 0 && schoolCards.length > 0) ? '' : 'none';
            }
        }

        // Bind events
        searchInput.addEventListener('input', filterSchools);
        schoolTypeFilter.addEventListener('change', filterSchools);
        divisionFilter.addEventListener('change', filterSchools);

        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            schoolTypeFilter.value = '';
            divisionFilter.value = '';
            filterSchools();
        });
    });
</script>
@endsection
