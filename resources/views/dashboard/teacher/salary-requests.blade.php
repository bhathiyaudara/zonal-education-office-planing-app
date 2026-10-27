@extends('layouts.dashboard')
@section('title', 'Salary Requests')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Salary Requests</h1>
                <p class="zems-header-subtitle">Request salary certificates and documents</p>
            </div>
        </div>
    </div>

    @if(!$teacher)
    <div class="zems-card-base p-6 text-center border-dashed border-2">
        <p class="text-amber-600 font-bold">Teacher profile not found.</p>
    </div>
    @else
    {{-- Request Form --}}
    <div class="zems-card-base p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-6">New Salary Request</h2>
        <form method="POST" action="{{ route('teacher.salary.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="zems-input-label">Document Type *</label>
                    <select name="documentType" required class="zems-input">
                        <option value="">Select...</option>
                        <option>Salary Certificate</option>
                        <option>Service Certificate</option>
                        <option>Leave Certificate</option>
                        <option>Salary Increment</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">Period *</label>
                    <select name="periodType" required class="zems-input">
                        <option value="">Select...</option>
                        <option>Monthly</option>
                        <option>Quarterly</option>
                        <option>Yearly</option>
                        <option>Custom</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="zems-input-label">Details</label>
                <textarea name="periodDetails" rows="2" class="zems-input" placeholder="Any additional details..."></textarea>
            </div>
            <div class="pt-2 flex justify-end">
                <button type="submit" class="zems-btn-primary">
                    <svg class="w-4 h-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    {{-- Previous Requests --}}
    <div class="zems-card-base p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-6">My Requests</h2>
        <div class="space-y-4">
            @forelse($requests as $req)
            <div class="flex items-center justify-between p-5 rounded-xl border border-slate-200 hover:border-slate-300 transition-colors shadow-sm">
                <div>
                    <p class="font-bold text-slate-900">{{ $req->documentType }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $req->periodType }} {{ $req->periodDetails ? '— ' . $req->periodDetails : '' }}</p>
                    <p class="text-xs font-semibold text-slate-400 mt-2 uppercase tracking-wider">{{ $req->createdAt->format('M d, Y') }}</p>
                </div>
                <span class="zems-badge-secondary
                    {{ $req->status === 'PENDING' ? '!bg-amber-100 !text-amber-700 !border-amber-200' : '' }}
                    {{ $req->status === 'APPROVED' ? '!bg-emerald-100 !text-emerald-700 !border-emerald-200' : '' }}
                    {{ $req->status === 'REJECTED' ? '!bg-red-100 !text-red-700 !border-red-200' : '' }}
                    {{ $req->status === 'COMPLETED' ? '!bg-blue-100 !text-blue-700 !border-blue-200' : '' }}">
                    {{ $req->status }}
                </span>
            </div>
            @empty
            <div class="py-12 text-center text-slate-400 border-dashed border-2 rounded-xl border-slate-200">
                <svg class="w-12 h-12 opacity-20 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <p class="text-sm font-black uppercase tracking-wider">No salary requests yet.</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif
</div>
@endsection
