@extends('layouts.dashboard')
@section('title', 'Student Overview')

@section('content')
<div class="space-y-6 animate-fade-in">

    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Student Overview ({{ $academicYear }})</h1>
                <p class="zems-header-subtitle">Comprehensive student enrollment statistics across all schools in Wilgamuwa Zone</p>
            </div>
        </div>
        <button onclick="exportAllPagesStudentOverview()" class="zems-btn-secondary !bg-white/10 !text-white !border-white/20 hover:!bg-white/20 backdrop-blur-sm px-4 flex items-center gap-2" title="Export complete database records for all matching schools and grade-by-grade enrollments">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Export All Pages</span>
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Enrollment --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-2xl translate-x-1/2 -translate-y-1/2"></div>
            <p class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1">Total Enrollment</p>
            <h2 class="text-4xl font-black mb-2">{{ number_format($totalStudents) }}</h2>
            <div class="inline-flex items-center gap-2 text-xs font-medium bg-white/20 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <span>Across {{ $totalSchools }} Schools</span>
            </div>
        </div>

        {{-- Gender Split --}}
        <div class="zems-card-base p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gender Split</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-2xl font-black text-blue-600">{{ number_format($totalMale) }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Male Students</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-pink-600">{{ number_format($totalFemale) }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Female Students</p>
                </div>
            </div>
            @if($totalStudents > 0)
            <div class="mt-4 h-2 w-full bg-slate-100 rounded-full overflow-hidden flex">
                <div class="h-full bg-blue-500" style="width:{{ ($totalMale / $totalStudents) * 100 }}%;"></div>
                <div class="h-full bg-pink-500" style="width:{{ ($totalFemale / $totalStudents) * 100 }}%;"></div>
            </div>
            @endif
        </div>

        {{-- Quick Stats --}}
        <div class="zems-card-base p-6 flex flex-col justify-center">
            <p class="text-sm font-bold text-slate-600 mb-4 uppercase tracking-wider">Quick Stats</p>
            <div class="flex flex-col gap-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Wilgamuwa Division</span>
                    <span class="font-black text-slate-900">{{ number_format($wilgamuwaStudents) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Laggala Division</span>
                    <span class="font-black text-slate-900">{{ number_format($laggalaStudents) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="zems-card-base p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="studentSearchInput" placeholder="Search by school name or census no..." class="zems-input pl-10 h-[44px]">
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <select id="studentDivisionFilter" class="zems-input h-[44px]">
                <option value="All">All Divisions</option>
                <option value="Wilgamuwa">Wilgamuwa</option>
                <option value="Laggala">Laggala</option>
            </select>
        </div>
    </div>

    {{-- Schools Table --}}
    <div class="zems-table-container">
        <div class="overflow-x-auto">
            <table class="zems-table">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th w-12 text-center">#</th>
                        <th class="zems-table-th">School Details</th>
                        <th class="zems-table-th text-center">Totals</th>
                        <th class="zems-table-th text-center !text-blue-500">Male</th>
                        <th class="zems-table-th text-center !text-pink-500">Female</th>
                        <th class="zems-table-th text-center">Classes</th>
                        <th class="zems-table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    @forelse($schoolsData as $index => $school)
                    <tr class="zems-table-row student-row" data-id="{{ $school['id'] }}" data-name="{{ strtolower($school['name']) }}" data-census="{{ strtolower($school['censusNo']) }}" data-division="{{ $school['eduDivision'] }}">
                        <td class="zems-table-td text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="zems-table-td">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-lg shrink-0">
                                    {{ strtoupper(substr($school['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 leading-tight">{{ $school['name'] }}</h3>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        @if($school['censusNo'])
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">#{{ $school['censusNo'] }}</span>
                                        @endif
                                        <span class="text-xs text-slate-500 font-medium">{{ $school['eduDivision'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="zems-table-td text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-black bg-emerald-50 text-emerald-700">
                                {{ number_format($school['totalStudents']) }}
                            </span>
                        </td>
                        <td class="zems-table-td text-center font-black text-blue-600">{{ number_format($school['totalMale']) }}</td>
                        <td class="zems-table-td text-center font-black text-pink-600">{{ number_format($school['totalFemale']) }}</td>
                        <td class="zems-table-td text-center font-bold text-slate-600">{{ $school['totalClasses'] }}</td>
                        <td class="zems-table-td text-right">
                            <a href="{{ route('zonal.schools.show', $school['id']) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                View Details
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>

            {{-- Empty State --}}
            <div id="studentNoResults" style="{{ empty($schoolsData) ? '' : 'display:none;' }} padding:48px 24px;text-align:center;color:#94a3b8;">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="font-bold text-slate-500 uppercase tracking-widest text-sm">No schools found matching your search</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('studentSearchInput');
    const divisionFilter = document.getElementById('studentDivisionFilter');
    const rows = document.querySelectorAll('.student-row');
    const noResults = document.getElementById('studentNoResults');

    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const division = divisionFilter.value;
        let visibleCount = 0;

        rows.forEach(function(row, i) {
            const name = row.getAttribute('data-name') || '';
            const census = row.getAttribute('data-census') || '';
            const rowDivision = row.getAttribute('data-division') || '';

            const matchesSearch = !search || name.includes(search) || census.includes(search);
            const matchesDivision = division === 'All' || rowDivision === division;

            if (matchesSearch && matchesDivision) {
                row.style.display = '';
                visibleCount++;
                // Update row number
                row.querySelector('td').textContent = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }

    searchInput.addEventListener('input', filterTable);
    divisionFilter.addEventListener('change', filterTable);

    // Add hover effect to rows
    rows.forEach(function(row) {
        row.addEventListener('mouseenter', function() { row.style.background = '#f8fafc'; });
        row.addEventListener('mouseleave', function() { row.style.background = ''; });
    });
});

// Export CSV
function exportAllPagesStudentOverview() {
    const rows = document.querySelectorAll('.student-row');
    const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("zonal.schools.students.export") }}';
    form.style.display = 'none';

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    if (visibleRows.length < rows.length) {
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

function exportToCSV() {
    exportAllPagesStudentOverview();
}
</script>
@endsection
