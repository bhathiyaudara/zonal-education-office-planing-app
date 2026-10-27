@extends('layouts.dashboard')

@section('title', 'Reports')

@section('content')
<div class="space-y-6 animate-fade-in-up">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Reports</h1>
                <p class="zems-header-subtitle">Generate and download reports for schools, teachers and students</p>
            </div>
        </div>
    </div>

    <div class="zems-card-base p-12 text-center border-2 border-dashed border-slate-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-50 flex items-center justify-center">
            <svg class="w-8 h-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">Coming Soon</h3>
        <p class="mt-2 text-sm text-slate-500">This feature is under development.</p>
    </div>
</div>
@endsection
