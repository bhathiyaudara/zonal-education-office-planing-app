@extends('layouts.dashboard')
@section('title', 'School Teachers')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="zems-header">
        <div class="flex items-center justify-between flex-wrap gap-4 w-full">
            <div>
                <h1 class="zems-header-title">Teachers - {{ $school->name }}</h1>
                <p class="zems-header-subtitle">Staff directory for your school</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('school.teachers.export', request()->all()) }}" class="zems-btn-secondary !bg-white/10 !text-white !border-white/20 hover:!bg-white/20 backdrop-blur-sm px-4 flex items-center gap-2" title="Export complete database records for all filtered teachers">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export All Pages</span>
                </a>
                <a href="{{ route('school.teachers.create') }}" class="zems-btn-primary !bg-emerald-600 hover:!bg-emerald-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Teacher
            </a>
        </div>
    </div>

    <form method="GET" class="zems-card-base p-4 flex gap-3">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or NIC..." class="zems-input pl-10 h-[44px]">
        </div>
        <button class="zems-btn-primary h-[44px]">Search</button>
    </form>

    <div class="zems-table-container">
        <div class="overflow-x-auto">
            <table class="zems-table">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th">Name</th>
                        <th class="zems-table-th">NIC</th>
                        <th class="zems-table-th">Subject</th>
                        <th class="zems-table-th">Phone</th>
                        <th class="zems-table-th">Gender</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr class="zems-table-row">
                        <td class="zems-table-td font-bold text-slate-900">{{ $teacher->fullName ?: $teacher->nameWithInitials }}</td>
                        <td class="zems-table-td font-semibold text-slate-600">{{ $teacher->nic }}</td>
                        <td class="zems-table-td text-slate-600">{{ $teacher->appt_code ?: '-' }}</td>
                        <td class="zems-table-td text-slate-600">{{ $teacher->phone ?: '-' }}</td>
                        <td class="zems-table-td text-slate-600">{{ $teacher->gender ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <p class="text-sm font-black uppercase tracking-wider">No teachers found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $teachers->links() }}
</div>
@endsection
