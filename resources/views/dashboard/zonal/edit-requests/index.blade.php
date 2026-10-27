@extends('layouts.dashboard')

@section('title', 'Edit Requests')

@section('content')
<div class="space-y-6 animate-fade-in-up">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Edit Requests</h1>
                <p class="zems-header-subtitle">Review and manage teacher profile edit requests</p>
            </div>
        </div>
    </div>

    <div class="zems-card-base p-12 text-center border-2 border-dashed border-slate-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-blue-50 flex items-center justify-center">
            <svg class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">Coming Soon</h3>
        <p class="mt-2 text-sm text-slate-500">This feature is under development.</p>
    </div>
</div>
@endsection
