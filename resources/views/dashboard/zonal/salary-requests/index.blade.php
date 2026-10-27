@extends('layouts.dashboard')

@section('title', 'Salary Requests')

@section('content')
<div class="space-y-6 animate-fade-in-up">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Salary Requests</h1>
                <p class="zems-header-subtitle">Review and manage teacher salary certificate requests</p>
            </div>
        </div>
    </div>

    <div class="zems-card-base p-12 text-center border-2 border-dashed border-slate-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-emerald-50 flex items-center justify-center">
            <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">Coming Soon</h3>
        <p class="mt-2 text-sm text-slate-500">This feature is under development.</p>
    </div>
</div>
@endsection
